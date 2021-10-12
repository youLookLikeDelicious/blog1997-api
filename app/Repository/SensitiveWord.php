<?php
namespace App\Repository;

use Illuminate\Http\Request;
use App\Http\Resources\SensitiveWordCollection;
use App\Model\SensitiveWord as ModelSensitiveWord;
use App\Contract\Repository\SensitiveWord as RepositorySensitiveWord;

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
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all (Request $request)
    {
        $this->validateRequest($request);

        $sensitiveWordQuery = $this->buildQuery($request);

        $result = $sensitiveWordQuery->paginate($request->input('perPage', 10));

        return new SensitiveWordCollection($result);
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

        $sensitiveWordQuery = $this->model->selectRaw('id, word, category_id, created_at')
            ->with('category:id,name,rank');

        if ($categoryId = $request->input('category_id')) {
            $sensitiveWordQuery->where('category_id', $categoryId + 0);
        }

        if ($word = $request->input('word')) {
            $sensitiveWordQuery->where('word', 'like', "%{$word}%");
        }

        $sensitiveWordQuery->orderBy('created_at', 'desc');

        return $sensitiveWordQuery;
    }
}