<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostStoreRequest;
use App\Http\Resources\Api\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    
    public function index()
    {
    
        $post = Post::included()
            ->filter()
            ->sort()
            ->getOrPaginate();
        
        return PostResource::collection($post);
    }

    public function store(PostStoreRequest $request)
    {
        try{
            DB::beginTransaction();
            $post = Post::create($request->validated());
            DB::commit();
            return PostResource::make($post);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear el post',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    public function show(Post $post)
    {
        $post = Post::included()->findOrFail($post->id);
        return PostResource::make($post);
    }

    public function update(PostStoreRequest $request, Post $post)
    {
        try{
            DB::beginTransaction();
            $post->update($request->validated);
            DB::commit();
            return PostResource::make($post);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar el post',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
    
    public function destroy(Post $post)
    {
        $post->delete();
        return PostResource::make($post);
    }
}
