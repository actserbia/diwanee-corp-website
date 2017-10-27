<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Article;
use App\Tag;
use App\Constants\ArticleStatus;
use Illuminate\Support\Facades\Validator;
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

        $articles = Article::withTrashed()->with('author')->get()->toArray();
        return view('admin.articles.articles_list', ['articles' => $articles, 'statuses' => ArticleStatus::all]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all()->toArray();

        return view('admin.articles.articles_create', ['tags' => $tags, 'statuses' => ArticleStatus::all]);
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
            print_r($validator->errors()->all());die();
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
        
        return view('admin.articles.articles_edit', ['article' => $article, 'tags' => $tags, 'statuses' => ArticleStatus::all]);
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
        $article = Article::findOrFail($id);
        $article->delete();
        return redirect()->route('articles.index')->with('success', "The article <strong>" . $article->title . "</strong> has successfully been archived.");
    }

    private function validator(array $data) {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'publication' => 'nullable|exists:tags,id|checkTagType:publication',
            'brand' => 'nullable|exists:tags,id|checkTagType:brand',
            'category' => 'required|exists:tags,id|checkTagType:category',
            'influencer' => 'nullable|exists:tags,id|checkTagType:influencer',
            'subcategories.*' => 'exists:tags,id|checkTagType:subcategory',
            'external_url' => 'nullable|url'
        ]);
    }
}
