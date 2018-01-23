<?php

namespace App\Http\Controllers;

use File;
use Request;
use Response;
use Illuminate\Routing\Controller as BaseController;

class GraphiqlController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the graphql ui
     *
     * @return \Response
     */
    public function index()
    {
        //return Response::make(view('graphiql::index'), 200);
        return view('graphiql.index');

    }
}
