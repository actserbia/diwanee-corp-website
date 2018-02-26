<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\NodeType;
use Auth;


class ApiNodeTypesController extends Controller
{

    public function index() {
        return NodeType::all();
    }

    public function show($id) {
        $type = NodeType::with('nodes')->find($id);

        if (!$type) {
            $data = array('errors' => [__('messages.articles.not_exists', ['id' => $id])]);
            return response()->json($data, 404);
        }
        return $type;
    }

    public function typeahead() {
        $nodes = NodeType::select('id','name')->get();
        echo json_encode($nodes);
    }

}
