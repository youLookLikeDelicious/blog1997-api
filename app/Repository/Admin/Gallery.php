<?php
namespace App\Repository\Admin;

use App\Model\Gallery as ModelGallery;
use App\Contract\Repository\Gallery as RepositoryGallery;
use App\Facades\Upload;
use App\Http\Resources\CommonCollection;

class Gallery implements RepositoryGallery
{
    /**
     * gallery Model
     * @var \App\Model\Gallery
     */
    protected $gallery;

    public function  __construct(ModelGallery $gallery)
    {
        $this->gallery = $gallery;
    }

    /**
     * 获取相册的数量
     *
     * @return int
     */
    public function count() : int
    {
        $model = $this->gallery->selectRaw('count(id) as count')->first();

        return $model ? $model->count : 0;
    }

    /**
     * 分页获取数据
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all($request)
    {
        $this->validateRequest($request);

        $galleryQuery = $this->buildQuery($request);

        $data = new CommonCollection($galleryQuery->paginate($request->input('perPage', 10)));

        return $data;
    }

    /**
     * Validate incoming request
     *
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    protected function validateRequest($request)
    {
        $request->validate([
            'date' => 'sometimes|required|array',
            'date.*' => 'required|date'
        ]);
    }

    /**
     * Build Database query with request
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Query\Builder
     */
    protected function buildQuery($request)
    {
        $query = $this->gallery
            ->select('id', 'url', 'created_at', 'date_time')
            ->where('is_cover', 'no');

        if ($color = $request->input('color')) {
            preg_match_all('~\d+~', $color, $color);
            $color = implode(',', $color[0]);
            if ($color) {
                $query->whereRaw("Match (colors) AGAINST ('+{$color}' IN BOOLEAN MODE)");
            }
        }

        if ($date = $request->input('date')) {
            $query->whereBetween('date_time', [strtotime($date[0]), strtotime($date[1])]);
        }

        if ($keywords = $request->input('keywords')) {
            $query->whereRaw("Match (location, remark) AGAINST ('+{$keywords}' IN BOOLEAN MODE)");
        }

        return $query;
    }

    /**
     * 获取下一张封面
     *
     * @param int $id
     * @return ModelGallery
     */
    public function next(int $id)
    {
        $gallery = $this->gallery
            ->where('id', '>', $id)
            ->where('is_cover', 'no')
            ->first();

        return $gallery;
    }

    /**
     * 获取第一张图片的id
     *
     * @return ModelGallery
     */
    public function first()
    {
        $gallery = $this->gallery->first();

        return $gallery;
    }

    /**
     * 上传图片
     *
     * @param \App\Http\Requests\UploadImageRequest $request $request
     * @return void
     */
    public function store($request)
    {
        $data = $request->validate();

        // 开始上传
        $fileList = Upload::uploadImage($data['files'], 'gallery', null, null, false)
            ->createThumbnail('240')
            ->getFileList(false, true)
            ->toArray();

        ModelGallery::insert($fileList);
    }
}