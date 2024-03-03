<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'icon' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $photo = $request->file('icon');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photoPath = $photo->storeAs('photos', $photoName, 'public');

        $category = new Category();
        $category->title = $request->title;
        $category->icon = $photoPath;
        $category->type = $request->type;
        $category->save();

        return response()->json(['message' => 'Category added successfully'], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
        $photo = $request->file('icon');
        if ($photo) {
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('photos', $photoName, 'public');
            $category->icon = $photoPath || $category->icon;
        }

        $category->title = $request->title || $category->title;
        $category->type = $request->type || $category->type;
        $category->save();
        return response()->json(['message' => 'Category updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($category, Response::HTTP_OK);
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, Response::HTTP_OK);
    }

    public function getAllDependingOnType(Request $request)
    {
        $type = $request->query('type');
        $categories = Category::where('type', $type)->get();
        return response()->json($categories, Response::HTTP_OK);
    }

    public function getPlacesForOneCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $places = $category->places;

        return response()->json($places, Response::HTTP_OK);
    }

    public function getServicesForOneCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $services = $category->services;

        return response()->json($services, Response::HTTP_OK);
    }
}
