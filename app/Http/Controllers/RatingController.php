<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {

        $userId = Auth::id();

        $booking = Booking::where('status', '=', 'completed')
                            ->where('user_id', $userId)
                            ->where('place_id', $request->place_id)
                            ->where('service_id', $request->service_id)
                            ->first();

        if (!$booking) {
            return response()->json(['message' => 'you should have completed booking to rate'], Response::HTTP_NOT_ACCEPTABLE);
        }

        $rating = Rating::where('user_id', $userId)
        ->where('place_id', $request->place_id)
        ->where('service_id', $request->service_id)
        ->get();

        // $rating = Rating::where([
        //     'user_id' => $userId,
        //     'place_id' => $request->place_id,
        //     'service_id' => $request->service_id
        // ])->firstOrFail();

        // dd($userId);

        if (!$rating) {
            return response()->json(['message' => 'you rate before'], Response::HTTP_NOT_ACCEPTABLE);
        }

        $rating = new Rating();

        $rating->rate = $request->rate;
        $rating->user_id = $userId;
        $rating->place_id = $request->place_id ?? null;
        $rating->service_id = $request->service_id ?? null;

        $rating->save();

        return response()->json(['message' => 'rated successfully'], Response::HTTP_OK);
    }

    public function update(UpdateRatingRequest $request, $id)
    {
        $rating = Rating::findOrFail($id);

        if (!$rating) {
            return response()->json(['message' => 'rating not found'], Response::HTTP_NOT_FOUND);
        }

        $rating->rate = $request->rate ?? $request->rate;
        $rating->user_id = $request->user_id ?? $rating->user_id;
        $rating->place_id = $request->place_id ?? $rating->place_id;
        $rating->service_id = $request->service_id ?? $rating->service_id;

        $rating->save();

        return response()->json(['message' => 'rate updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);

        if (!$rating) {
            return response()->json(['message' => 'rate not found'], Response::HTTP_NOT_FOUND);
        }

        $rating->delete();
        return response()->json(['message' => 'rate deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $rating = Rating::findOrFail($id);

        if (!$rating) {
            return response()->json(['message' => 'rate not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($rating, Response::HTTP_OK);
    }

    public function index()
    {
        $ratings = Rating::all();
        return response()->json($ratings, Response::HTTP_OK);
    }
}
