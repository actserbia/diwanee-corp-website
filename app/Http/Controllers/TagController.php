<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
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
    *   @SWG\Response(response=405, description="validation exception"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    **/
    public function index(Request $request)
    {
        $params = $request->all();
        
        $tags = Tag::with('parents', 'children');
        if(isset($params['type'])) {
            $tags = $tags->where('type', '=', $params['type']);
        }

        $tagsData = $tags->get();

        return $tagsData;
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
     *   @SWG\Response(response=405, description="validation exception"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show($id)
    {
        return Tag::with('parents', 'children')->find($id);
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
     * @SWG\Response(response=405, description="validation exception"),
     * @SWG\Response(response=500, description="internal server error")
     * )
    **/
    public function store(Request $request)
    {
        $data = $request->all();

        $errors = $this->validateData($data, 0);
        if (!empty($errors)) {
            $data = array('errors' => $errors);
            return response()->json($data, 405);
        }

        $tag = new Tag;
        $tag->saveTag($data);

        return response()->json($tag, 201);
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
     *     @SWG\Response(response=404, description="Tag not found"),
     *     @SWG\Response(response=405, description="validation exception")
     * )
    */
    public function update($id, Request $request)
    {
        $data = $request->all();

        $errors = $this->validateData($data, $id);
        if (!empty($errors)) {
            $data = array('errors' => $errors);
            return response()->json($data, 405);
        }

        $tag = Tag::findOrFail($id);
        $tag->saveTag($data);

        return response()->json($tag, 200);
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
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return response()->json(null, 204);
    }

    private function validateData(array $data, $id) {
        $errors = array();

        $validator = $this->validator($data, $id);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        }

        if($data['type'] !== 'subcategory' && !empty($data['parents'])) {
            $errors[] = 'Only subcategory tags have parents.';
        }

        if($data['type'] !== 'category' && !empty($data['children'])) {
            $errors[] = 'Only category tags have children.';
        }

        return $errors;
    }
    
    private function validator(array $data, $id) {
        return Validator::make($data, [
            'name' => 'required|unique:tags,id,' . $id . '|max:255',
            'type' => 'required|exists:tags,type',
            'parents.*' => 'exists:tags,id|checkTagType:category',
            'children.*' => 'exists:tags,id|checkTagType:subcategory',
        ]);
    }
}
