<?php

namespace App\Http\Controllers;

use App\Traits\Weather;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    use Weather;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('home');
    }

    public function getWeather(Request $request)
    {
        return Weather::getMainData($request->latitude, $request->longitude);
    }
}
