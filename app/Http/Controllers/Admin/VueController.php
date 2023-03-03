<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VueController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware(['auth', 'rbac'])->only('index');
    }
    /**
     * index
     * Method GET
     */
    public function index (Request $request)
    {
        return view('admin');
    }

    public function login (Request $request) {
        return view('auth.auth');
    }
}
