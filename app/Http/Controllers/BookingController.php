<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(CreateCategoryRequest $request)
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

    public function update(UpdateCategoryRequest $request, $id)
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

    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        if ($booking->created_at > Carbon::now()->addDay()) {
            return response()->json(['message' => 'Booking cannot canceled after 24 hours'], Response::HTTP_NOT_ACCEPTABLE);
        }

        $booking->status = 'canceled';
        $booking->save();

        return response()->json(['message' => 'booking updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking) {
            return response()->json(['message' => 'booking not found'], Response::HTTP_NOT_FOUND);
        }

        $booking->delete();
        return response()->json(['message' => 'booking deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($booking, Response::HTTP_OK);
    }

    public function index()
    {
        $bookings = Booking::with('place.category', 'service.category')->get();
        return response()->json($bookings, Response::HTTP_OK);
    }

    public function getAllForAuthUser()
    {
        $userId = Auth::id();

        $bookings = Booking::with('place.category', 'service.category')
                            ->where('user_id', $userId)
                            ->get();

        $bookings->load(['place.averageRating', 'service.averageRating']);

        return response()->json($bookings, Response::HTTP_OK);
    }

    public function getAllForFuture()
    {
        $bookings = Booking::where('created_at', '>', Carbon::now())
                            ->where('status', '!=', 'canceled')
                            ->select('starting_date', 'ending_date')
                            ->get();
        return response()->json($bookings, Response::HTTP_OK);
    }
}
