<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{

    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string',
        //     'type' => 'required|string',
        //     'icon' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        // ]);

        $photo = $request->file('icon');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photoPath = $photo->storeAs('photos', $photoName, 'public');

        $service = new service();
        $service->title = $request->title;
        $service->image = $photoPath;
        $service->description = $request->description;
        $service->tag = $request->tag;
        $service->duration = $request->duration;
        $service->price = $request->price;
        $service->max_capacity = $request->max_capacity;
        $service->category_id = $request->category_id;
        $service->vendor_id = $request->vendor_id;
        $service->save();

        // dd($service);

        return response()->json(['message' => 'service added successfully'], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $service = service::findOrFail($id);

        if (!$service) {
            return response()->json(['message' => 'service not found'], Response::HTTP_NOT_FOUND);
        }

        $photo = $request->file('icon');
        if ($photo) {
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('photos', $photoName, 'public');
            $service->image = $photoPath;
        }

        $service->title = $request->title || $service->title;
        $service->description = $request->description || $service->description;
        $service->duration = $request->duration || $service->duration;
        $service->price = $request->price || $service->price;
        $service->max_capacity = $request->max_capacity || $service->max_capacity;
        $service->category_id = $request->category_id || $service->category_id;
        $service->vendor_id = $request->vendor_id || $service->vendor_id;
        $service->featured = $request->featured || $service->featured;
        $service->bookable = $request->bookable || $service->bookable;
        $service->available = $request->available || $service->available;
        $service->save();

        return response()->json(['message' => 'service updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $service = service::findOrFail($id);

        if (!$service) {
            return response()->json(['message' => 'service not found'], Response::HTTP_NOT_FOUND);
        }

        $service->delete();
        return response()->json(['message' => 'service deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $service = service::findOrFail($id);

        if (!$service) {
            return response()->json(['message' => 'service not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($service, Response::HTTP_OK);
    }

    public function index()
    {
        $services = service::with('availableTimes', 'serviceImages')
        ->where('available', true)
        ->orderBy('price', 'asc')
        ->select('services.*', DB::raw('AVG(ratings.rate) as rating'))
        ->leftJoin('ratings', 'services.id', '=', 'ratings.service_id')
        ->groupBy('services.id')
        ->get();
        return response()->json($services, Response::HTTP_OK);
    }

    public function getServicesForOneUser($id)
    {
        $services = service::with('availableTimes', 'serviceImages')
        ->where('available', true)
        ->where('vendor_id', $id)
        ->orderBy('price', 'asc')
        ->select('services.*', DB::raw('AVG(ratings.rate) as rating'))
        ->leftJoin('ratings', 'services.id', '=', 'ratings.service_id')
        ->groupBy('services.id')
        ->get();
        return response()->json($services, Response::HTTP_OK);
    }

    public function getAllFeatured()
    {
        $services = service::with('availableTimes', 'serviceImages')
        ->where('featured', true)
        ->orderBy('price', 'asc')
        ->select('services.*', DB::raw('AVG(ratings.rate) as rating'))
        ->leftJoin('ratings', 'services.id', '=', 'ratings.service_id')
        ->groupBy('services.id')
        ->get();
        return response()->json($services, Response::HTTP_OK);
    }

}
