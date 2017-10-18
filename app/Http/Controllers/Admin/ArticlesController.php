<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Article;
use App\Tag;
use App\Constants\ArticleStatus;
use Validator;
use Auth;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Article::withTrashed()->restore();

        $articles = Article::all()->toArray();
        return view('admin.articles.articles_list', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all()->toArray();
        $status = ArticleStatus::populateStatus();

        return view('admin.articles.articles_create', ['tags' => $tags, 'status' => $status]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validator($data);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $article = new Article;
        $data['id_author'] = Auth::id();
        $article->saveArticle($data);

        return redirect()->route('articles.index')->with('success', "The article <strong>" . $article->title . "</strong> has successfully been created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::findOrFail($id)->toArray();
        return view('admin.articles.articles_delete', ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        $tags = Tag::all()->toArray();
        $status = ArticleStatus::populateStatus();
        
        return view('admin.articles.articles_edit', ['article' => $article, 'tags' => $tags, 'status' => $status]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = $this->validator($data);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
  
        $article = Article::find($id);
        $article->saveArticle($data);

        return redirect()->route('articles.index')->with('success', "The article <strong>" . $article->title . "</strong> has successfully been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        $article->delete();
        return redirect()->route('articles.index')->with('success', "The article <strong>" . $article->title . "</strong> has successfully been archived.");
    }

    private function validator(array $data) {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'category' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'external_url' => 'url',
        ]);
    }
}
