<?php
namespace App\Repository\Admin;

use App\Models\Gallery as ModelGallery;
use App\Contract\Repository\Gallery as RepositoryGallery;
use App\Facades\Upload;
use App\Http\Resources\CommonCollection;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Gallery implements RepositoryGallery
{
    /**
     * gallery Model
     * @var \App\Models\Gallery
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
    public function list($request)
    {
        $this->validateRequest($request);

        $galleryQuery = $this->buildQuery($request);

        $data = new CommonCollection($galleryQuery->paginate($request->input('perPage', 10)));

        return $data;
    }

    /**
     * 获取有gps信息的所有图片
     *
     * @return void
     */
    public function all($request)
    {
        $galleries = $this->gallery->select(['id', 'url', 'lng_lat', 'location', 'date_time'])
            ->where('lng_lat', '<>', '')
            ->get();

        return $galleries;
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

        // 根据相册查询
        if ($id = $request->input('id')) {
            $query->whereHas('album', function ($builder) use ($id) {
                $builder->where('album_id', $id);
            });
        }

        if ($keywords = $request->input('keywords')) {
            $query->whereRaw("Match (location, remark) AGAINST ('+{$keywords}' IN BOOLEAN MODE)");
        }

        $query->orderBy('created_at', 'desc');

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
            ->withTrashed()
            ->where('user_id', auth()->id())
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
     * @param \App\Http\Requests\UploadImageRequest $request
     * @return \App\Models\Album
     */
    public function store($request)
    {
        $data = $request->validated();

        $userId =  Auth::id();

        // 开始上传
        $fileList = Upload::uploadImage($data['files'], 'gallery', null, null, false)
            ->createThumbnail('240')
            ->getFileList(false, true)
            ->map(function ($item) use ($userId) {
                $item['user_id'] = $userId;
                return new ModelGallery($item);
            });

        $album = Album::firstOrCreate(['name' => $data['album'], 'user_id' => $userId]);
        $album->refresh();
        $galleries = $album->galleries()->saveMany($fileList);
        
        // 更新相册封面
        if ($album->gallery_is_auto === 1) {
            $album->update([ 'gallery_id' => $galleries->last()->id ]);
        }

        return $album;
    }

    /**
     * 获取所有相册
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function albumAll()
    {
        return Album::select(['id', 'name'])->get();
    }

    /**
     * 分页获取相册
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function albumList($request)
    {
        $albums = $this->buildAlbumQuery($request)->withCount('galleries as total')->paginate($request->input('perPage'));

        return new CommonCollection($albums);
    }

    protected function buildAlbumQuery($request)
    {
        $query = Album::select(['id', 'name', 'gallery_id', 'desc'])
            ->with('gallery:id,url');

        if ($name = $request->input('name')) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $query->orderBy('created_at', 'desc');

        return $query;
    }
}