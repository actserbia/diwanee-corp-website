<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Article;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Rules\CheckSTContent;


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
        
        $this->formatArticles($articlesData, false, true);
        
        return $articlesData;
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
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show($id)
    {
        $article = Article::with('elements', 'tags')->find($id);
        $article->changeFormat(false, true);
        $article->addSliderToElements();
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
    public function store(Request $request)
    {
        $data = $request->all();

        $errors = $this->validateData($data);
        if (!empty($errors)) {
            $data = array('errors' => $errors);
            return response()->json($data, 405);
        }

        $article = new Article;
        $data['id_author'] = Auth::guard('api')->id();
        $article->saveArticle($data);
        
        return response()->json($article, 201);
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
    public function update($id, Request $request)
    {
        $data = $request->all();
        
        $errors = $this->validateData($data);
        if (!empty($errors)) {
            $data = array('errors' => $errors);
            return response()->json($data, 405);
        }

        $article = Article::findOrFail($id);
        $article->saveArticle($data);

        return response()->json($article, 200);
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
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(null, 204);
    }

    private function validateData(array $data) {
        $errors = array();

        $validator = $this->validator($data);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        }

        return $errors;
    }

    private function validator(array $data) {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'external_url' => 'nullable|url',
            'publication' => 'nullable|exists:tags,id|checkTagType:publication',
            'brand' => 'nullable|exists:tags,id|checkTagType:brand',
            'category' => 'required|exists:tags,id|checkTagType:category',
            'influencer' => 'nullable|exists:tags,id|checkTagType:influencer',
            'subcategories.*' => 'exists:tags,id|checkTagType:subcategory',
            'content' => [new CheckSTContent]
        ]);
    }
    
    private function tagsValidator(array $data) {
        $rules = [];
        foreach($data['tags'] as $index => $tag) {
            $rules['tags.' . $index] = 'exists:tags,name';
}
        return Validator::make($data, $rules);
    }
    
    private function formatArticles($articles, $jsonEncode = true, $toHtml = false) {
        foreach($articles as $article) {
            $article->changeFormat($jsonEncode, $toHtml);
            $article->addSliderToElements();
        }
    }
}
