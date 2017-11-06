<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Validation\Validators;

class ApiTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    /**
    * @SWG\Get(
    *     path="/tags",
    *     tags={"tags"},
    *     summary="List tags",
    *     operationId="index",
    * @SWG\Parameter(
    *     name="type",
    *     in="query",
    *     description="Tag Type",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Response(response=200, description="successful operation", @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Tag"))),
    *   @SWG\Response(response=400, description="validation exception"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    **/
    public function index(Request $request)
    {
        $params = $request->all();
        
        $tags = Tag::with('parents', 'children')->withTypeIfParamExists($params)->get();

        return $tags;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    /**
     * @SWG\Get(
     *   path="/tags/{id}",
     *   tags={"tags"},
     *   summary="Tag by ID",
     *   operationId="show",
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Target tag",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="successful operation", @SWG\Schema(ref="#/definitions/Tag")),
     *   @SWG\Response(response=400, description="validation exception"),
     *   @SWG\Response(response=404, description="tag not found"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show($id)
    {
        $tag = Tag::with('parents', 'children')->find($id);
        if (!$tag) {
            $data = array('errors' => [__('messages.articles.not_exist', ['id' => $id])]);
            return response()->json($data, 404);
        }
        
        return $tag;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    /**
     * @SWG\Post(
     *    path="/tags",
     *    tags={"tags"},
     *    summary="Create tag",
     *    operationId="store",
     *    @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="Tag object that will be created",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Tag"),
     *    ),
     * @SWG\Response(response=201, description="successful operation", @SWG\Schema(ref="#/definitions/Tag")),
     * @SWG\Response(response=400, description="validation exception"),
     * @SWG\Response(response=500, description="internal server error")
     * )
    **/
    public function store(Request $request)
    {
        $data = $request->all();

        Validators::tagsFormValidator($data)->validate();

        $tag = new Tag;
        if($tag->saveTag($data)) {
            return response()->json($tag, 201);
        } else {
            $data = array('errors' => [__('messages.tags.store_error', ['name' => $tag->name])]);
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
     *     path="/tags/{id}",
     *     tags={"tags"},
     *     operationId="update",
     *     summary="Update an existing tag",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Target tag",
     *     required=true,
     *     type="integer"
     *     ),
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Tag object that needs to be updated",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/Tag"),
     *     ),
     *     @SWG\Response(response=200, description="successful operation", @SWG\Schema(ref="#/definitions/Tag")),
     *     @SWG\Response(response=404, description="tag not found"),
     *     @SWG\Response(response=400, description="validation exception")
     * )
    */
    public function update($id, Request $request)
    {
        $data = $request->all();

        Validators::tagsFormValidator($data)->validate();

        $tag = Tag::find($id);
        if (!$tag) {
            $data = array('errors' => [__('messages.tags.not_exist', ['id' => $id])]);
            return response()->json($data, 404);
        }
        
        $tag->saveTag($data);
        if($tag->saveTag($data)) {
            return response()->json($tag, 200);
        } else {
            $data = array('errors' => [__('messages.tags.update_error', ['name' => $tag->name])]);
            return response()->json($data, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    /**
     * @SWG\Delete(
     *     path="/tags/{id}",
     *     tags={"tags"},
     *     operationId="destroy",
     *     summary="Delete an existing tag",
     *     description="",
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Target tag",
     *        required=true,
     *        type="integer"
     *     ),
     *     @SWG\Response(response=204, description="successful operation"),
     *     @SWG\Response(response=404, description="Tag not found")
     * )
    */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            $data = array('errors' => [__('messages.tags.not_exist', ['id' => $id])]);
            return response()->json($data, 404);
        }
        
        if($tag->delete()) {
            return response()->json(null, 204);
        } else {
            $data = array('errors' => [__('messages.tags.destroy_error', ['name' => $tag->name])]);
            return response()->json($data, 500);
        }
    }
}
