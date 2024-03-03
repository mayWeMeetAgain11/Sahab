<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\PlaceImage;
use App\Models\SpecialDay;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{

    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string',
        //     'type' => 'required|string',
        //     'icon' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        // ]);

        $place = new Place();

        $photo = $request->file('icon');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photoPath = $photo->storeAs('photos', $photoName, 'public');


        $place->title = $request->title;
        $place->image = $photoPath;
        $place->address = $request->address;
        $place->description = $request->description;
        $place->weekday_price = $request->weekday_price;
        $place->weekend_price = $request->weekend_price;
        $place->tag = $request->tag;
        $place->category_id = $request->category_id;
        $place->vendor_id = $request->vendor_id;

        if ($request->amenities) {
            $place->amenities()->attach($request->amenities);
        }
        $place->save();

        return response()->json(['message' => 'place added successfully'], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $place = Place::findOrFail($id);

        if (!$place) {
            return response()->json(['message' => 'place not found'], Response::HTTP_NOT_FOUND);
        }

        $photo = $request->file('icon');
        if ($photo) {
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('photos', $photoName, 'public');
            $place->image = $photoPath;
        }

        $place->title = $request->title || $place->title;
        $place->address = $request->address || $place->address;
        $place->description = $request->description || $place->description;
        $place->address = $request->address || $place->address;
        $place->weekday_price = $request->weekday_price || $place->weekday_price;
        $place->weekend_price = $request->weekend_price || $place->weekend_price;
        $place->tag = $request->tag || $place->tag;
        $place->category_id = $request->category_id || $place->category_id;
        $place->vendor_id = $request->vendor_id || $place->vendor_id;
        $place->featured = $request->featured || $place->featured;
        $place->bookable = $request->bookable || $place->bookable;
        $place->available = $request->available || $place->available;
        if ($request->amenities) {
            $place->amenities()->detach();
            $place->amenities()->attach($request->amenities);
        }
        $place->save();

        // $place->update($request->all());
        return response()->json(['message' => 'place updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $place = Place::findOrFail($id);

        if (!$place) {
            return response()->json(['message' => 'place not found'], Response::HTTP_NOT_FOUND);
        }

        $place->delete();
        return response()->json(['message' => 'place deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $place = Place::with('amenities', 'specialDays', 'placeImages')->findOrFail($id);

        if (!$place) {
            return response()->json(['message' => 'place not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($place, Response::HTTP_OK);
    }

    public function index()
    {
        $places = DB::table('places')
        // Place::with('amenities', 'specialDays', 'placeImages')
        ->leftJoin('ratings', 'places.id', '=', 'ratings.place_id')
        // ->leftJoin('special_days', 'places.id', '=', 'special_days.place_id')
        // ->leftJoin('place_images', 'places.id', '=', 'place_images.place_id')
        ->where('places.available', true)
        ->orderBy('places.weekday_price', 'asc')
        // ->joinLateral($latestPosts, 'specialDays')
        // ->withAverageRating()
        // ->select(
        //     // 'places.*',
        //     DB::raw('AVG(ratings.rate) as rating, places.*')
        // )
        // ->groupBy('ratings.place_id')
        ->get();

        foreach ($places as $place) {
            // $place->ratings = floatval($place->rating);
            unset($place->rating); // Remove the 'rating' field, as it's been assigned to 'ratings'

            $place->special_days = SpecialDay::where('place_id', $place->id)->get();
            $place->place_images = PlaceImage::where('place_id', $place->id)->get();
            // Assuming there's a relationship defined between Place model and Amenities, uncomment the line below if there's no such relation
            // $place->amenities = Amenity::where('place_id', $place->id)->get();
        }

        return response()->json($places, Response::HTTP_OK);
    }

    public function getPlacesForOneUser()
    {

        $userId = Auth::id();

        // dd($userId);

        $places = Place::with('amenities')
        ->leftJoin('ratings', 'places.id', '=', 'ratings.place_id')
        ->leftJoin('special_days', 'places.id', '=', 'special_days.place_id')
        ->leftJoin('place_images', 'places.id', '=', 'place_images.place_id')
        ->where('places.available', true)
        ->where('places.vendor_id', $userId)
        ->orderBy('places.weekday_price', 'asc')
        // ->withAverageRating()
        // ->select(
        //     'places.*',
        //     DB::raw('AVG(ratings.rate) as rating')
        // )
        // ->groupBy('place_id', 'places.id')
        ->get();
        return response()->json($places, Response::HTTP_OK);
    }

    public function getAllFeatured()
    {
        $places = Place::with('amenities', 'specialDays', 'placeImages')
        ->where('available', true)
        ->where('featured', true)
        ->orderBy('weekday_price', 'asc')
        ->select('places.*', DB::raw('AVG(ratings.rate) as rating'))
        ->leftJoin('ratings', 'places.id', '=', 'ratings.place_id')
        ->groupBy('places.id')
        ->get();
        return response()->json($places, Response::HTTP_OK);
    }
}
