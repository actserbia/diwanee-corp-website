<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Arcanedev\LogViewer\Entities\Log;
use Arcanedev\LogViewer\Entities\LogEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Illuminate\Routing\Route;

class DashboardController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counts = [
            'users' => \DB::table('users')->count(),
            'users_inactive' => \DB::table('users')->where('active', false)->count(),
            'articles' => \DB::table('articles')->count(),
            'articles_deleted' =>  \App\Article::onlyTrashed()->count(),
            'tags' => \DB::table('tags')->count(),
        ];

        return view('admin.home', ['counts' => $counts]);
    }

}
