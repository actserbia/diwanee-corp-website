<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Article;
use Auth;
use Validator;


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
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    **/
    public function index(Request $request)
    {
        $articles =  Article::with('elements', 'tags')->orderBy('created_at', 'desc');

        $params = $request->all();
        if(isset($params['tags'])) {
            $validator = $this->tagsValidator($params);
            if ($validator->fails()) {
                $data = array('errors' => $validator->errors()->all());
                return response()->json($data, 400);
            }
            
            foreach($params['tags'] as $tag) {
                $articles = $articles->whereHas('tags', function($q) use($tag) {
                    $q->where('name', '=', $tag);
                });
            }
        }
        
        if(isset($params['active'])) {
            $status = $params['active'] ? 1 : 0;
            $articles = $articles->withStatus($status);
        }
        
        $skip = isset($params['skip']) && is_numeric($params['skip']) ? $params['skip'] : 0;
        $limit = isset($params['limit']) && is_numeric($params['limit']) ? $params['limit'] : 0;
        if($skip > 0 && $limit == 0) {
            $limit = 10;
        }
        if($skip > 0 || $limit > 0) {
            $articles = $articles->skip($skip)->take($limit);
        }
        
        $articlesData = $articles->get();
        
        $this->formatArticles($articlesData, false);
        
        return $articlesData;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validator($data);
        if ($validator->fails()) {
            $data = array('errors' => $validator->errors()->all());
            return response()->json($data, 400);
        }

        $article = new Article;
        $data['id_author'] = Auth::guard('api')->id();
        $article->saveArticle($data);

        return response()->json($article, 201);
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
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show($id)
    {
        $article = Article::with('elements', 'tags')->find($id);
        $article->addSliderToElements();
        return $article;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $data = $request->all();

        $validator = $this->validator($data);
        if ($validator->fails()) {
            $data = array('errors' => $validator->errors()->all());
            return response()->json($data, 400);
        }

        $article = Article::find($id);
        $article->saveArticle($data);

        return response()->json($article, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Article  $article
     * @return Response
     */
    public function destroy(Article $article) {
        $article->delete();

        return response()->json(null, 204);
    }

    private function validator(array $data) {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'category' => 'required'
        ]);
    }
    
    private function tagsValidator(array $data) {
        $rules = [];
        foreach($data['tags'] as $index => $tag) {
            $rules['tags.' . $index] = 'exists:tags,name';
}
        return Validator::make($data, $rules);
    }
    
    private function formatArticles($articles, $encode) {
        foreach($articles as $article) {
            $article->changeJsonEncodeFormat($encode);
            $article->addSliderToElements();
        }
    }
}
