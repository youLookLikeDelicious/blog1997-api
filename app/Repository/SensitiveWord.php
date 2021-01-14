<?php
namespace App\Repository;

use App\Facades\Page;
use App\Model\SensitiveWord as ModelSensitiveWord;
use App\Contract\Repository\SensitiveWord as RepositorySensitiveWord;
use App\Model\SensitiveWordCategory;
use Illuminate\Http\Request;

class SensitiveWord implements RepositorySensitiveWord
{
    protected $model;

    public function __construct(ModelSensitiveWord $model)
    {
        $this->model = $model;
    }

    /**
     * 获取所有的 word字段
     * 
     * @return array
     */
    public function getWordList () : array
    {
        $result = $this->model->select('word')->orderBy('word')->get()->toArray();
        $result = array_column($result, 'word');

        return $result;
    }

    /**
     * Get paginated sensitive word from database
     *
     * @param Request $request
     * @return array
     */
    public function all (Request $request) : array
    {
        $this->validateRequest($request);

        // 获取分类列表
        $categoryList = $this->getCategoryList();

        if (!count($categoryList)) {
            return [];
        }

        $sensitiveWordQuery = $this->buildQuery($request);

        $result = Page::paginate($sensitiveWordQuery);
        
        $result['categoryList'] = $categoryList;

        $result['categoryId'] = $request->input('category_id', 0) + 0;

        return $result;
    }

    protected function validateRequest(Request $request)
    {
        $request->validate([
            'category_id' => 'sometimes|required|integer|min:1',
            'word' => 'sometimes|required'
        ]);
    }

    /**
     * Build eloquent query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery (Request $request) {

        $sensitiveWordQuery = $this->model->selectRaw('id, word, category_id, false as editAble');

        if ($categoryId = $request->input('category_id')) {
            $sensitiveWordQuery->where('category_id', $categoryId + 0);
        }

        if ($word = $request->input('word')) {
            $sensitiveWordQuery->where('word', 'like', "%{$word}%");
        }

        $sensitiveWordQuery->orderBy('created_at', 'desc');

        return $sensitiveWordQuery;
    }

    /**
     * 获取敏感词分类列表
     *
     * @return array
     */
    protected function getCategoryList()
    {
        $categoryList = SensitiveWordCategory::select(['id', 'name'])->get()->toArray();

        array_unshift($categoryList, ['id' => 0, 'name' => '所有分类']);

        return $categoryList;
    }
}