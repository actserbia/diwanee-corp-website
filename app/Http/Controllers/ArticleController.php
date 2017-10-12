<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Luracast\Restler\RestException;

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
    public function index()
    {
        return Article::with('elements', 'tags')->get();
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
    public function show($id)
    {
        return Article::with('elements', 'tags')->find($id);
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
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }

    private function validator(array $data) {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'category' => 'required'
        ]);
    }
}
