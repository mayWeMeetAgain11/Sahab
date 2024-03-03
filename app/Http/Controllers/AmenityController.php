<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Models\Amenity;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class AmenityController extends Controller
{

    public function store(Request $request)
    {

        $photo = $request->file('icon');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photoPath = $photo->storeAs('photos', $photoName, 'public');

        $amenity = new Amenity();
        $amenity->title = $request->title;
        $amenity->icon = $photoPath;
        $amenity->save();

        return response()->json(['message' => 'amenity added successfully'], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $amenity = Amenity::findOrFail($id);

        if (!$amenity) {
            return response()->json(['message' => 'amn$amenity not found'], Response::HTTP_NOT_FOUND);
        }

        $photo = $request->file('icon');
        if ($photo) {
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('photos', $photoName, 'public');
            $amenity->icon = $photoPath || $amenity->icon;
        }

        $amenity->title = $request->title || $amenity->title;
        // $amenity->type = $request->type || $amenity->type;
        $amenity->save();
        return response()->json(['message' => 'Amenity updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $amenity = Amenity::findOrFail($id);

        if (!$amenity) {
            return response()->json(['message' => 'amenity not found'], Response::HTTP_NOT_FOUND);
        }

        $amenity->delete();
        return response()->json(['message' => 'amenity deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $amenity = Amenity::findOrFail($id);

        if (!$amenity) {
            return response()->json(['message' => 'amenity not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($amenity, Response::HTTP_OK);
    }

    public function index()
    {
        $amenities = Amenity::all();
        return response()->json($amenities, Response::HTTP_OK);
    }

    public function getPlacesForOneAmenity($id)
    {
        $amenity = Amenity::find($id);

        if (!$amenity) {
            return response()->json(['message' => 'Amenity not found'], Response::HTTP_NOT_FOUND);
        }

        $places = $amenity->places;

        return response()->json($places, Response::HTTP_OK);
    }

}
