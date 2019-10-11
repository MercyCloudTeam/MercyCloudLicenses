<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    protected $version = "V1.0.0 20191001";

    public function index()
    {
        $str = env('APP_NAME','MercyCloud')." Service is running normally. Version:{$this->version} ";
        return $str;
    }
    //
}
