<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStaticContentRequest;
use App\Http\Requests\UpdateStaticContentRequest;
use App\Models\StaticContent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StaticContentController extends Controller
{
    public function store(Request $request)
    {

        $staticContent = new StaticContent();
        $staticContent->title = $request->title;
        $staticContent->content = $request->content;
        $staticContent->save();

        return response()->json(['message' => 'content added successfully'], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $staticContent = StaticContent::findOrFail($id);

        if (!$staticContent) {
            return response()->json(['message' => 'content not found'], Response::HTTP_NOT_FOUND);
        }

        $staticContent = new StaticContent();
        $staticContent->title = $request->title;
        $staticContent->content = $request->content;
        $staticContent->save();

        return response()->json(['message' => 'content updated successfully'], Response::HTTP_OK);
    }

    public function index()
    {
        $staticContents = StaticContent::all();
        return response()->json($staticContents, Response::HTTP_OK);
    }
}
