<?php

namespace App\Jobs;

use Exception;
use DOMDocument;
use App\Models\SiteMap;
use App\Models\FailedJobs;
use DOMXPath;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateSiteMap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 请求的路径
     * 
     * @var string
     */
    protected $requestUrl;

    protected $priority;

    protected $frequent; 

    /**
     * 二级xml下地址的最大数量
     * 
     * @var string
     */
    protected $linkMaxNum;

    /**
     * 自定义的二级xml地址
     * 
     * @var string
     */
    protected $pathLevel;

    public $tries = 2;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 3;

    /**
     * Create a new job instance.
     * 
     * @param $path 请求的地址
     * @param $priority sitemap的优先级
     * @param $frequent 更新频率
     *
     * @return void
     */
    public function __construct($requestUrl, $priority, $frequent, $pathLevel)
    {
        $this->requestUrl = ltrim($requestUrl, '/');

        $this->priority = $priority;

        $this->frequent = $frequent;

        $this->pathLevel = $pathLevel;

        $this->linkMaxNum = config('app.sitemap_max_num');
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SiteMap $siteMapModel)
    {
        $appUrl = config('app.url');

        // 获取请求的地址
        $requestLink =  $appUrl. '/' .$this->requestUrl;
        $suffix = '';
        
        // 从模型中获取当前的地址
        $siteMap = $siteMapModel->select('id', 'sitemap_url')
            ->where('sitemap_url', $requestLink)
            ->first();

        // 如果当前的地址已经保存，无需任何操作
        if ($siteMap) {
            return;
        }
        
        // /api/article 的article部分
        $urlPatten = $this->pathLevel?: explode('/', $this->requestUrl)[0];

        // 获取二级xml相关的数据
        $siteMapLevel2 = $siteMapModel->select(['id', 'sitemap_url', 'link_num', 'created_at'])
            ->where('sitemap_url', 'like', "{$appUrl}/sitemap_{$urlPatten}%")
            ->first();

        // 如果指定了二级xml的路径，无需自动校准xml的后缀
        if ($this->pathLevel) {
            $url = "{$appUrl}/sitemap_{$this->pathLevel}.xml";
        } else {
            // 没有指定二级xml，自动验证文件规则
            if ($siteMapLevel2 && $siteMapLevel2->link_num >= $this->linkMaxNum) {
                $preSuffix = explode('_', rtrim($siteMapLevel2->sitemap_url, '.xml'));
                $suffix = '_' . (end($preSuffix) + 1);
            } else {
                $suffix = '_1';
            }

            $url = "{$appUrl}/sitemap_{$urlPatten}{$suffix}.xml";
        }
        
        try{
            DB::beginTransaction();
            
            // 如果二级xml地址不存在
            if (!$siteMapLevel2 || ($siteMapLevel2 && $siteMapLevel2->link_num >= $this->linkMaxNum)) {

                // 二级sitemap的地址
                $levelTwoSiteMap = $siteMapModel->create([
                    'sitemap_url' => $url,
                    'level' => 2,
                    'link_num' => 1
                ]);

                // 一级sitemap的数据量+1
                $siteMapModel->where('level', 1)
                    ->increment('link_num');

                $this->updateSiteMap($url);
            } else {
                // 如果存在，获取二级sitemap 并将link_num + 1
                $levelTwoSiteMap = $siteMapModel->select(['id'])
                    ->where('sitemap_url', $url)
                    ->first();
                    
                $levelTwoSiteMap->increment('link_num');
            }

            // 插入三级sitemap
            $siteMapModel->create([
                'sitemap_url' => $requestLink,
                'changefreq' => $this->frequent,
                'priority' => $this->priority,
                'level' => 3,
                'parent_id' => $levelTwoSiteMap->id
            ]);

            $this->updateLeveTwolSiteMap("sitemap_{$urlPatten}{$suffix}.xml", $requestLink);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->failed($e);
        }
    }

    /**
     * 处理队列工作失败的事件
     */
    public function failed($exception = null, $errorMessage = '')
    {
        FailedJobs::create([
            'connection' => 'redis',
            'queue' => 'queues:redis_queue',
            'payload' => "priority = {$this->priority},frequent = {$this->frequent},path = {$this->requestUrl}",
            'exception' => $errorMessage?: $exception->getMessage()
        ]);
    }

    /**
     * 更新 sitemap.xml
     * @param location 二级sitemap的地址
     * @param $lastMod 文档最后的修改时间
     */
    protected function updateSiteMap ($location) {
        
        $siteMapPath = $this->getMapPath('sitemap.xml');

        if (!is_file($siteMapPath)) {
            Artisan::call('sitemap:init');
        }
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->load($siteMapPath);

        // 创建相关元素
        $newSitemap = $dom->createElement('sitemap');

        $newLocal = $dom->createElement('loc', $location);
        $newLastMod = $dom->createElement('lastmod', str_replace(' ', 'T', date('Y-m-d H:i:s') . '+08:00'));

        // 将创建的节点插入到文档中
        $newSitemap->appendChild($newLocal);
        $newSitemap->appendChild($newLastMod);

        $dom->documentElement->appendChild($newSitemap);

        $dom->save($siteMapPath);
    }

    /**
     * 创建二级sitemap
     */
    public function createTwoLevelSiteMap ($path) {
        $appUrl = config('app.url');

        $content = <<< EOT
<?xml version="1.0" encoding="utf-8"?>
<?xml-stylesheet type="text/xsl" href="{$appUrl}/sitemap_2.xsl"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">
</urlset>
EOT;
        file_put_contents($path, $content);
    }

    /**
     * 更新二级地图
     * @param String $filePath 二级sitemap地址
     * @param String $link 保存的连接
     */
    public function updateLeveTwolSiteMap ($filePath, $link) {

        $siteMapPath = $this->getMapPath($filePath);
        
        if (!is_file($siteMapPath)) {
            $this->createTwoLevelSiteMap($siteMapPath);
        }

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->load($siteMapPath);

        // 创建相关元素
        $newSitemap = $dom->createElement('url');

        $newLocal = $dom->createElement('loc', $link);
        $newMobile = $dom->createElement('mobile', 'pc,mobile');
        $newPriority = $dom->createElement('priority', $this->priority);
        $newChangeFrequent = $dom->createElement('changefreq', $this->frequent);
        $newLastMod = $dom->createElement('lastmod', str_replace(' ', 'T', date('Y-m-d H:i:s') . '+08:00'));

        // 将创建的节点插入到文档中
        $newSitemap->appendChild($newLocal);
        $newSitemap->appendChild($newMobile);
        $newSitemap->appendChild($newChangeFrequent);
        $newSitemap->appendChild($newPriority);
        $newSitemap->appendChild($newLastMod);

        $dom->documentElement->appendChild($newSitemap);

        $dom->save($siteMapPath);
    }

    /**
     * 根据运行的环境，生成sitemap的地址
     *
     * @param string $path
     * @return string
     */
    protected function getMapPath ($path)
    {
        if (App::runningUnitTests()) {
            return base_path('tests/' . $path);
        }

        return storage_path('sitemap/' . $path);
    }
}
