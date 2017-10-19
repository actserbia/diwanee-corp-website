<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ArticleController;


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
        $articleController = new ArticleController();
        $data['articles'] = $this->formatOutput($articleController->index($request));

        $tagController = new TagController();
        $data['tags'] = $this->formatOutput($tagController->index($request));

        return $data;

    }

    private function formatOutput($articles) {
        $articlesOutput = array();
        foreach($articles as $article) {
            $articlesOutput[$article->id] = $article;
        }
        return $articlesOutput;
    }
}
