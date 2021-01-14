<?php

namespace App\Http\Controllers\Admin;

use App\Model\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\Admin\LogRepository;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param LogRepository $repository
     * @param String $type
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, LogRepository $repository, $type = 'user')
    {        
        $logs = $repository->all($request, $type);

        return response()->success($logs);
    }
}
