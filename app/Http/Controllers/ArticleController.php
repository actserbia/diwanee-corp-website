<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Article;
use Auth;
use App\Validation\Validators;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    /**
    *   @SWG\Get(
    *   path="/articles",
    *   tags={"articles"},
    *   summary="List articles",
    *   operationId="index",
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
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=400, description="validation error"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    **/
    public function index(Request $request) {
        $params = $request->all();
        $validatorData = Validators::validateData('articlesIndexValidator', $params);
        if (!empty($validatorData)) {
            return response()->json($validatorData, 400);
        }
        
        $articles = Article::with('elements', 'tags')
            ->withTagsIfParamExists($params, 'name')
            ->withActiveIfParamExists($params)
            ->orderBy('created_at', 'desc')
            ->paginateIfParamExists($params);
        
        $this->formatArticles($articles, false, true);
        
        return $articles;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    /**
     * @SWG\Get(
     *   path="/articles/{id}",
     *   tags={"articles"},
     *   summary="Article by ID",
     *   operationId="show",
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Target article.",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="active",
     *     in="path",
     *     description="eg. ",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation", @SWG\Schema(ref="#/definitions/Article"),),
     *   @SWG\Response(response=400, description="validation error"),
     *   @SWG\Response(response=405, description="article not exists"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show($id) {
        $article = Article::with('elements', 'tags')->find($id);
        
        if (!$article) {
            $data = array('errors' => [__('messages.articles.not_exist', ['id' => $id])]);
            return response()->json($data, 404);
        }
        
        $article->changeFormat(false, true);
        return $article;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    /**
     * @SWG\Post(
     *    path="/articles",
     *    tags={"articles"},
     *    summary="Create article",
     *    operationId="store",
     *    @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="Article object that will be created",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Article"),
     *    ),
     * @SWG\Response(response=201, description="successful operation")),
     * @SWG\Response(response=405, description="validation exception"),
     * @SWG\Response(response=500, description="internal server error")
     * )
    **/
    public function store(Request $request) {
        $data = $request->all();

        $validatorData = Validators::validateData('articlesFormValidator', $data);
        if (!empty($validatorData)) {
            return response()->json($validatorData, 405);
        }

        $article = new Article;
        $data['id_author'] = Auth::guard('api')->id();
        if($article->saveArticle($data)) {
            return response()->json($article, 201);
        } else {
            $data = array('errors' => [__('messages.articles.store_success', ['title' => $article->title])]);
            return response()->json($data, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    /**
     * @SWG\Put(
     *    path="/articles/{id}",
     *    tags={"articles"},
     *    summary="Update an existing article",
     *    operationId="update",
     *    @SWG\Parameter(
     *      name="id",
     *      in="path",
     *      description="Target article",
     *      required=true,
     *      type="integer"
     *    ),
     *    @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="Article object that will be updated",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Article"),
     *    ),
     * @SWG\Response(response=200, description="successful operation"),
     * @SWG\Response(response=405, description="validation exception"),
     * @SWG\Response(response=500, description="internal server error")
     * )
    **/
    public function update($id, Request $request) {
        $data = $request->all();
        
        $validatorData = Validators::validateData('articlesFormValidator', $data);
        if (!empty($validatorData)) {
            return response()->json($validatorData, 405);
        }

        $article = Article::find($id);
        if (!$article) {
            $data = array('errors' => [__('messages.articles.not_exist', ['id' => $id])]);
            return response()->json($data, 404);
        }
        
        if($article->saveArticle($data)) {
            return response()->json($article, 200);
        } else {
            $data = array('errors' => [__('messages.articles.update_error', ['title' => $article->title])]);
            return response()->json($data, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Article  $article
     * @return Response
     */
    /**
     * @SWG\Delete(
     *     path="/articles/{id}",
     *     tags={"articles"},
     *     operationId="destroy",
     *     summary="Delete an existing article",
     *     description="",
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Target article",
     *        required=true,
     *        type="integer"
     *     ),
     *     @SWG\Response(response=204, description="successful operation"),
     *     @SWG\Response(response=404, description="Article not found")
     * )
    */
    public function destroy($id) {
        $article = Article::find($id);
        if (!$article) {
            $data = array('errors' => [__('messages.articles.not_exist', ['id' => $id])]);
            return response()->json($data, 404);
        }
        
        if($article->delete()) {
            return response()->json(null, 204);
        } else {
            $data = array('errors' => [__('messages.articles.destroy_error', ['title' => $article->title])]);
            return response()->json($data, 500);
        }
    }
    
    private function formatArticles($articles, $jsonEncode = true, $toHtml = false) {
        foreach($articles as $article) {
            $article->changeFormat($jsonEncode, $toHtml);
        }
    }
}
