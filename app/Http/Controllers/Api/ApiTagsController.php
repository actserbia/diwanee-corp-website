<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Constants\NodeStatus;

class ApiTagsController extends Controller
{

    public function index(Request $request)
    {
        $params = $request->all();

        $tags = Tag::with('parents', 'children')
            ->withCount(['nodes' => function ($q) { $q->whereIn('status', NodeStatus::activeStatuses); }])
            ->filterByParam('type', $params, 'type')
            ->get();
        return $tags;
    }


    public function show($id)
    {
        $tag = Tag::with('parents', 'children')->find($id);
        if (!$tag) {
            $data = array('errors' => [__('messages.tags.not_exists', ['id' => $id])]);
            return response()->json($data, 404);
        }
        return $tag;
    }


}
