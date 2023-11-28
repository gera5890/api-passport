<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoryRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        $categories = Category::included()
            ->filter()
            ->sort()
            ->getAllorPaginate();
        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {
        
        try{
            DB::beginTransaction();
            $category = Category::create($request->validated());
            DB::commit();
            return CategoryResource::make($category);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show(Category $category)
    {
        $result = Category::included()->findOrFail($category->id);
        return CategoryResource::make($result);
    }

    public function update(Request $request, Category $category)
    {
        
        try{
            DB::beginTransaction();
            $category->update($request->validated);
            DB::commit();
            return CategoryResource::make($category);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error' => 'Error al crear la categoria', 'message' => $e->getMessage()], 500);
        }
    }

  
    public function destroy(Category $category)
    {
    
        $category->delete();
        return CategoryResource::make($category);
    }
}
