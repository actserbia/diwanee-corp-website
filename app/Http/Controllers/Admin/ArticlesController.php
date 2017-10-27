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
    public function index() {
        //Article::withTrashed()->restore();

        $articles = Article::withTrashed()->with('author')->get()->toArray();
        
        $statuses = ArticleStatus::all;
        
        return view('admin.articles.articles_list', compact('articles', 'statuses'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $article = Article::findOrFail($id)->toArray();
        
        return view('admin.articles.articles_delete', compact('article'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $tags = Tag::all()->toArray();
        $statuses = ArticleStatus::all;

        return view('admin.articles.articles_create', compact('tags', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->all();

        $validator = $this->validator($data);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $article = new Article;
        $data['id_author'] = Auth::id();
        
        $successName = $article->saveArticle($data) ? 'success' : 'error';
        
        return redirect()->route('articles.index')->with($successName, __('messages.articles.store_' . $successName, ['title' => $article->title]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $article = Article::findOrFail($id);

        $tags = Tag::all()->toArray();
        $statuses = ArticleStatus::all;
        
        return view('admin.articles.articles_edit', compact('article', 'tags', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $data = $request->all();
        
        $validator = $this->validator($data);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
  
        $article = Article::findOrFail($id);
        
        $successName = $article->saveArticle($data) ? 'success' : 'error';
        
        return redirect()->route('articles.index')->with($successName, __('messages.articles.update_' . $successName, ['title' => $article->title]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $article = Article::findOrFail($id);
        
        $successName = $article->delete() ? 'success' : 'error';
        return redirect()->route('articles.index')->with($successName, __('messages.articles.destroy_' . $successName, ['title' => $article->title]));
        
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
