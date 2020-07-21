<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();

        return response()->json($articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'announcement' => ['required', 'string'],
            'text' => ['required', 'string'],
            'author_id' => ['required', 'integer'],
            'tag_ids.*' => ['integer']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $article = Article::create($request->all());
        $article->tags()->sync($request->tag_ids);

        return response()->json([
            'success' => true,
            'article' => $article
        ]);
    }

    /**
     * Display the specified resource.
     *
    * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['string'],
            'announcement' => ['string'],
            'text' => ['string'],
            'author_id' => ['integer'],
            'tag_ids.*' => ['integer']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->toArray();
        unset($data['tag_ids']);
        $article->update($data);
        $article->tags()->sync($request->tag_ids);

        return response()->json([
            'success' => true,
            'article' => $article->with('tags')->get()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
