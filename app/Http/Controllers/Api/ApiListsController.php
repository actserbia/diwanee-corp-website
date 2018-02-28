<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\NodeList;


class ApiListsController extends Controller
{

    public function index() {

        $nodeObject = new NodeList();
        $objects = $nodeObject->get();

        return $objects;
    }

    public function typeahead() {

        $lists = NodeList::select('id','name')->get();
        $results = array();
        foreach($lists->toArray() as $arrList) {
            $results[] = array_map('strval', $arrList );
        }
        echo json_encode($results);
    }


}
