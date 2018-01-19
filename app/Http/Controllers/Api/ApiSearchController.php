<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Article;
use App\Tag;
use App\Validation\Validators;
use App\Constants\ElementType;

class ApiSearchController extends Controller
{
    /**
    *   @SWG\Get(
    *   path="/search/articles",
    *   tags={"search"},
    *   summary="Search articles",
    *   operationId="articles",
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
    *   @SWG\Parameter(
    *     name="elementsTypes[]",
    *     in="query",
    *     description="Elements types to filter by",
    *     required=false,
    *     type="array",
    *     @SWG\Items(type="string"),
    *     collectionFormat="multi"
    *   ),
    *   @SWG\Parameter(
    *     name="videoProviders[]",
    *     in="query",
    *     description="Video providers to filter by",
    *     required=false,
    *     type="array",
    *     @SWG\Items(type="string"),
    *     collectionFormat="multi"
    *   ),
    *   @SWG\Parameter(
    *     name="remoteIds[]",
    *     in="query",
    *     description="Video remote ids to filter by",
    *     required=false,
    *     type="array",
    *     @SWG\Items(type="string"),
    *     collectionFormat="multi"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=400, description="validation error"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    **/
    public function articles(Request $request) {
        $params = $request->all();

        Validators::articlesValidator($params)->validate();
    
        $articles = Article::withTrashed()->with('elements', 'tags')
            ->withAttributesEqual('ids', $params, 'id')
            ->withAttributesLike('title', $params, 'title')
            ->withAttributesEqual('status', $params, 'status')
            ->withAttributesLike('meta_title', $params, 'meta_title')
            ->withAttributesLike('meta_description', $params, 'meta_description')
            ->withAttributesLike('meta_keywords', $params, 'meta_keywords')
            ->withAttributesLike('content_description', $params, 'content_description')
            ->withAttributesLike('external_url', $params, 'external_url')
            ->withTags('tags', $params, 'name')
            ->withActive($params)
            ->withElements('elementsTypes', $params)
            ->withElements('videoProviders', $params, ElementType::DiwaneeVideo, 'source')
            ->withElements('remoteIds', $params, ElementType::DiwaneeVideo, 'remote_id')
            ->withAuthors('authorsNames', $params, 'name')
            ->withAuthors('authorsEmails', $params, 'email')
            ->withAuthors('authorsRoles', $params, 'role')
            ->orderBy('created_at', 'desc')
            ->withPagination($params);
        
        Article::formatArticles($articles, false, true);
        
        return $articles;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    /**
    * @SWG\Get(
    *     path="/search/tags",
    *     tags={"search"},
    *     summary="Search tags",
    *     operationId="tags",
    *   @SWG\Parameter(
    *     name="types[]",
    *     in="query",
    *     description="Tags types to filter by",
    *     required=false,
    *     type="array",
    *     @SWG\Items(type="string"),
    *     collectionFormat="multi"
    *   ),
    *   @SWG\Response(response=200, description="successful operation", @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Tag"))),
    *   @SWG\Response(response=400, description="validation exception"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    **/
    public function tags(Request $request) {
        $params = $request->all();
        
        Validators::tagsValidator($params)->validate();
        
        $tags = Tag::with('parents', 'children')
            ->withAttributesEqual('ids', $params, 'id')
            ->withAttributesEqual('names', $params, 'name', false)
            ->withAttributesEqual('types', $params, 'type')
            ->withPagination($params);

        return $tags;
    }
}
