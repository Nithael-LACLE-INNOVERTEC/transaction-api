<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $result = Category::all();
        $result = Category::select(['id', 'name'])->get();
        // Get collection of resource categories
        $result = CategoryResource::collection($result);

        // Return result
        return response()->json($result, Response::HTTP_ACCEPTED);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        // Copy data in array
        $data = $request->validated();

        // Create category in database
        $category = Category::create($data);

        // Create resource
        $category = new CategoryResource($category);

        // Return result
        return response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find
        $category = Category::find($id);
        if (!$category)
            return response()->json(['message' => "L'information demandé n'existe pas"], Response::HTTP_NOT_FOUND);

        // Create resourece
        $result = new CategoryResource($category);

        // Return result
        return response()->json($result, Response::HTTP_ACCEPTED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {
        // Copy data in array
        $data = $request->validated();

        // Find
        $category = Category::find($id);
        if (!$category)
            return response()->json(['message' => "L'information demandé n'existe pas"], Response::HTTP_NOT_FOUND);

        // Update
        $category->update($data);

        // Create resourece
        $result = new CategoryResource($category);

        // Return result
        return response()->json($result, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        // Find
        $category = Category::find($id);
        if (!$category)
            return response()->json(['message' => "L'information demandé n'existe pas"], Response::HTTP_NOT_FOUND);

        // Update
        $category->delete();

        // Return result
        return response()->json(["message" => "Suppression reussie"], Response::HTTP_OK);
    }
}
