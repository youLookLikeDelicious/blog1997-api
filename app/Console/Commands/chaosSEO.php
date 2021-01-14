<?php

namespace App\Console\Commands;

use Exception;
use App\Model\SiteMap;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Foundation\Application;

class ChaosSEO extends Command
{
    /**
     * application instance
     *
     * @var Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap file';

    /**
     * sitemap xsl模板
     * @var string
     */
    protected $sitemapXSL;

    /**
     * sitemap_2 xsl
     * @var string
     */
    protected $sitemapXSL2;

    /**
     * sitemap xml模板
     * @var string
     */
    protected $sitemapXML;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct();

        $this->app = $app;

        $this->sitemapXSL = include(__DIR__ . '/stubs/sitemap.xsl.stub.php');
        $this->sitemapXSL2 =  include(__DIR__ . '/stubs/sitemap.xsl.2.stub.php');
        
        $this->sitemapXML = file_get_contents(__DIR__ . '/stubs/sitemap.xml.stub'); 
        $this->sitemapXML = str_replace('APP_URL', env('APP_URL'), $this->sitemapXML);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        $this->initXMLFile();

        $this->attemptUpdateGitIgnore();

        // 向SiteMap模型中插入sitemap.xml的记录
        $url = env('APP_URL') . '/sitemap.xml';

        // 判断模型中是否有记录
        $count = SiteMap::selectRaw('count(id) as count')->first()->count;

        if ($count) {
            DB::statement('truncate xy_sitemap_info');
        }

        SiteMap::create([
            'level' => 1,
            'priority' => 1,
            'sitemap_url' => $url,
            'mobile' => 'pc,mobile',
            'changefreq'  => 'weekly',
        ]);

        $this->info('sitemap initialize successful');
    }

    /**
     * 生成sitemap相关的文件
     * 
     * @return void
     */
    public function initXMLFile () {
        // 获取public下的文件列表
        $fileList = scandir(public_path());
        $fileList = array_filter($fileList, function ($v) {
            return strpos($v, 'sitemap') === 0;
        });

        if ($fileList) {
            array_map(function ($v) {
                unlink(public_path($v));
            }, $fileList);
        }

        // 创建 sitemap_index文件
        file_put_contents($this->getFile('sitemap.xml'), $this->sitemapXML);

        // 创建 sitemap_index xsl文件
        file_put_contents($this->getFile('sitemap.xsl'), $this->sitemapXSL);


        // 创建sitemap_2.xsl 样式文件
        file_put_contents($this->getFile('sitemap_2.xsl'), $this->sitemapXSL2);
    }

    /**
     * 更新 .gitignore文件 
     * 
     * @return void
     */
    public function attemptUpdateGitIgnore () {
        $gitIgnoreFilePath = base_path('.gitignore');
        if (is_file($gitIgnoreFilePath)) {
            $gitIgnoreContent = file_get_contents($gitIgnoreFilePath);
            $appendContent = '/public/sitemap*';
            if (strpos($gitIgnoreContent, $appendContent) === false) {
                file_put_contents($gitIgnoreFilePath, "\n" . $appendContent, FILE_APPEND);
            }
        }
    }

    /**
     * 获取网站地图相关的文件
     */
    public function getFile ($fileName) {
        if (! $fileName) {
            return;
        }

        return $this->runningUnitTests()
            ? base_path("tests/{$fileName}")
            : public_path($fileName);
    }

    /**
     * Determine if the application is running unit tests.
     *
     * @return bool
     */
    protected function runningUnitTests()
    {
        return $this->app->runningInConsole() && $this->app->runningUnitTests();
    }
}
