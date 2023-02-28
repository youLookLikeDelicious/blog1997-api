<?php
namespace App\Repository\Admin;

use App\Models\Manual as ModelManual;
use App\Contract\Repository\Manual as RepositoryManual;
use App\Http\Resources\CommonCollection;

class Manual implements RepositoryManual
{
    protected $manual;

    public function __construct(ModelManual $manual)
    {
        $this->manual = $manual;
    }
    
    public function index($request)
    {
        $query = $this->manual->select('id', 'name', 'cover','updated_at');

        if ($name = $request->name) {
            $query->whereRaw("MATCH(name, summary) AGAINST ('+{$name}*' IN BOOLEAN MODE)");
        }

        $data = $query->paginate($request->input('perPage', 10));

        return new CommonCollection($data);
    }
}
