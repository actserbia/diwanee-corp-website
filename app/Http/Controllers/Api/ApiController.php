<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiTagsController;
use App\Http\Controllers\Api\ApiArticlesController;


class ApiController extends Controller
{
    /**
     * Display a listing of the articles and tags.
     *
     * @return Response
     */
    /**
    *   @SWG\Get(
    *   path="/",
    *   summary="List articles and tags",
    *   operationId="all",
    *   @SWG\Parameter(
    *     name="active",
    *     in="query",
    *     description="Filter results based on query string value.",
    *     required=false,
    *     enum={"true", "false"},
    *     default="true",
    *     type="string"
    *   ),
    * @SWG\Parameter(
    *     name="skip",
    *     in="query",
    *     description="(default: 0)",
    *     required=false,
    *     default=0,
    *     type="integer"
    *   ),
    * @SWG\Parameter(
    *     name="limit",
    *     in="query",
    *     description="(default: 0)",
    *     required=false,
    *     default=0,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tags[]",
    *     in="query",
    *     description="Tags to filter by",
    *     required=false,
    *     type="array",
    *     @SWG\Items(type="string"),
    *     collectionFormat="multi"
    *   ),
    * @SWG\Parameter(
    *     name="type",
    *     in="query",
    *     description="Tag Type",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    **/
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
