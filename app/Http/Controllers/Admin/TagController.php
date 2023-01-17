<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;
use App\Contract\Repository\Tag as Repository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Tag management
 * 
 * 文章标签管理
 */
class TagController extends Controller
{
    /**
     * Display article tag records.
     *
     * 获取标签列表
     * @queryParam parent_id 父标签ID
     * @queryParam name      标签名称
     * @queryParam p         请求的页数
     * @responseFile response/admin/tag/index.json
     * @param Request $request
     * @param Repository $tag
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Repository $tag)
    {
        $data = $tag->all($request);

        return $data->toResponse($request);
    }

    /**
     * Show the resource for creating a new resource.
     *
     * 创建标签的时候,获取顶级标签
     * 
     * @responseFile response/admin/tag/create.json
     * @param Repository $tag
     * @return \Illuminate\Http\Response
     */
    public function create(Repository $tag)
    {
        $data = $tag->flatted(true);

        return response()->success($data);
    }

    /**
     * Store newly created resource in storage.
     *
     * 新建标签
     * 如果标签上传图片,保存之,然后备份一个webp格式图片
     * 
     * @bodyParam name        string       required 标签名称
     * @bodyParam cover       string|image          标签封面,当封面是图片的时候,自动保存图片,并返回图片保存的路径
     * @bodyParam parent_id   int          required 标签父ID,-1表示用户创建的自定义标签,0表示管理员创建的顶级标签,>1表示管理员创建的子级标签
     * @bodyParam description string                标签的描述
     * @responseFile response/admin/tag/store.json
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        $data = $request->validated();
        
        $this->authorize('create', [Tag::class, $data]);
        
        Tag::create($data);

        return response()->success('', '标签添加成功');
    }

    /**
     * Update the specified resource in storage.
     *
     * 更新标签
     * 如果有更新封面行为,会移除之前的封面
     * 
     * @urlParam tag 标签ID
     * @bodyParam name        string       required 标签名称
     * @bodyParam cover       string|image          标签封面,当封面是图片的时候,自动保存图片,并返回图片保存的路径
     * @bodyParam parent_id   int          required 标签父ID,-1表示用户创建的自定义标签,0表示管理员创建的顶级标签,>1表示管理员创建的子级标签
     * @bodyParam description string                标签的描述
     * @responseFile response/admin/tag/update.json
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $data = $request->validated();

        $tag->update($data);
        
        return response()->success('', '标签修改成功');
    }

    /**
     * Destroy the specified resource from storage.
     *
     * 删除标签
     * 同时会尝试移除标签图片
     * 
     * @urlParam tag 标签ID
     * @responseFile response/admin/tag/destroy.json
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        DB::transaction(function () use ($tag) {
            $tag->delete();
        });

        return response()->success('', '标签删除成功');
    }

    /**
     * Get details of tag
     * 
     * 获取标签的详情
     * @urlParam  id required The ID of the post.
     * 
     * @param Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return response()->success($tag);
    }
}
