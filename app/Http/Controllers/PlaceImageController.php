<?php

namespace App\Http\Controllers;

use App\Models\PlaceImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlaceImageController extends Controller
{
    public function store(Request $request)
    {

        $placeImage = new PlaceImage();

        $photo = $request->file('image');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photoPath = $photo->storeAs('photos', $photoName, 'public');


        $placeImage->place_id = $request->place_id;
        $placeImage->image = $photoPath;

        $placeImage->save();

        return response()->json(['message' => 'image added successfully'], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $placeImage = PlaceImage::findOrFail($id);

        if (!$placeImage) {
            return response()->json(['message' => 'image not found'], Response::HTTP_NOT_FOUND);
        }

        $photo = $request->file('icon');
        if ($photo) {
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('photos', $photoName, 'public');
            $placeImage->image = $photoPath;
        }

        $placeImage->title = $request->title ?? $placeImage->title;
        $placeImage->address = $request->address ?? $placeImage->address;

        $placeImage->save();

        return response()->json(['message' => 'image updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $placeImage = PlaceImage::findOrFail($id);

        if (!$placeImage) {
            return response()->json(['message' => 'image not found'], Response::HTTP_NOT_FOUND);
        }

        $placeImage->delete();
        return response()->json(['message' => 'image deleted successfully'], Response::HTTP_OK);
    }

    public function deleteMany(Request $request)
    {
        $placeImage = PlaceImage::whereIn('id', $request->ids)->delete();

        return response()->json(['message' => 'images deleted successfully'], Response::HTTP_OK);
    }

    public function getAllForOnePlace($id)
    {
        $placeImages = PlaceImage::where('place_id', $id)->get();
        return response()->json($placeImages, Response::HTTP_OK);
    }

}
