<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiTagsController;
use App\Http\Controllers\Api\ApiArticlesController;


class ApiController extends Controller
{

    public function all(Request $request)
    {
        $articleController = new ApiArticlesController();
        $data['articles'] = $this->formatOutput($articleController->index($request));

        $tagController = new ApiTagsController();
        $data['tags'] = $this->formatOutput($tagController->index($request));

        return $data;

    }

    private function formatOutput($items) {
        $itemsOutput = array();
        foreach($items as $item) {
            $itemsOutput[$item->id] = $item;
        }
        return $itemsOutput;
    }
}
