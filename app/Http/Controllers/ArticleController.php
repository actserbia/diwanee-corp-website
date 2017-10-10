<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Article;
use App\Tag;
use App\Constants\TagType;
use App\Constants\ArticleStatus;
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
        return 'index';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $tags = Tag::all();
        $status = ArticleStatus::populateStatus();
        return view('article-create', ['tags' => $tags, 'status' => $status]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = $this->makeArticleValidator($request);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $article = new Article;
        $article->saveArticle($request);

        return redirect('/admin/articles');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        //one parent
        //$allCategorySubcategories = Tag::where('id_parent', '=', $article->category->id)->get();
        //more parents
        $allCategorySubcategories = $article->category->children;

        $tags = Tag::all();
        $status = ArticleStatus::populateStatus();
        return view('article-edit', ['article' => $article, 'tags' => $tags, 'subcategories' => $allCategorySubcategories, 'status' => $status]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $validator = $this->makeArticleValidator($request);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $article = Article::find($id);
        $article->saveArticle($request);

        return redirect('/admin/articles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    private function makeArticleValidator($request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'type' => 'required',
            'category' => 'required'
        ]);
        return $validator;
    }
}
