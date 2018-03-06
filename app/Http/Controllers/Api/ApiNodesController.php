<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Node;
use App\Constants\NodeStatus;
use Illuminate\Http\Request;

use Auth;


class ApiNodesController extends Controller
{

    public function index($type_id) {

        $objects = [];
        if(isset($type_id)) {
            $nodeObject = new Node(['node_type_id' => $type_id]);
            $objects = $nodeObject::where('node_type_id', '=', $type_id)->get();
        }
        return $objects;
    }

    public function typeahead($type_id, Request $request) {

        $reqNode = filter_var($request->server('HTTP_REFERER'), FILTER_SANITIZE_NUMBER_INT);

        $nodes = Node::select('id','title as name')
            ->where('node_type_id', '=', $type_id)
            ->where('id', '!=', $reqNode)
            ->whereIn('status', NodeStatus::activeStatuses)
            ->get();
        $results = array();
        foreach($nodes->toArray() as $arrNode) {
            $results[] = array_map('strval', $arrNode );
        }
        echo json_encode($results);
    }

    public function show($id) {
        $article = Node::with('elements', 'tags')->find($id);

        if (!$article) {
            $data = array('errors' => [__('messages.articles.not_exists', ['id' => $id])]);
            return response()->json($data, 404);
        }
        return $article;
    }


}
