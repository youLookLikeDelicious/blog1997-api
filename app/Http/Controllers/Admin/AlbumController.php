<?php

namespace App\Http\Controllers\Admin;

use App\Models\Album;
use Illuminate\Http\Request;
use App\Contract\Repository\Gallery;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AlbumRequest;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Gallery $gallery)
    {
        $albums = $gallery->albumList($request);

        return $albums->toResponse($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AlbumRequest $request)
    {
        Album::create($request->validated());
        
        return response()->success('', '添加成功');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(AlbumRequest $request, Album $album)
    {
        $album->update($request->validated());
        
        return response()->success('', '更新成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        $album->delete();

        return response()->success('', '删除成功');
    }

    public function all(Gallery $gallery)
    {
        $albums = $gallery->albumAll();

        return response()->success($albums);
    }
}
