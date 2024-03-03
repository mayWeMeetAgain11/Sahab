<?php

namespace App\Http\Controllers;

use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceImageController extends Controller
{
    public function store(Request $request)
    {

        $serviceImage = new ServiceImage();

        $photo = $request->file('image');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photoPath = $photo->storeAs('photos', $photoName, 'public');


        $serviceImage->service_id = $request->service_id;
        $serviceImage->image = $photoPath;

        $serviceImage->save();

        return response()->json(['message' => 'image added successfully'], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $serviceImage = ServiceImage::findOrFail($id);

        if (!$serviceImage) {
            return response()->json(['message' => 'image not found'], Response::HTTP_NOT_FOUND);
        }

        $photo = $request->file('image');
        if ($photo) {
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('photos', $photoName, 'public');
            $serviceImage->image = $photoPath;
        }

        $serviceImage->title = $request->title ?? $serviceImage->title;
        $serviceImage->address = $request->address ?? $serviceImage->address;

        $serviceImage->save();

        return response()->json(['message' => 'image updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $serviceImage = ServiceImage::findOrFail($id);

        if (!$serviceImage) {
            return response()->json(['message' => 'image not found'], Response::HTTP_NOT_FOUND);
        }

        $serviceImage->delete();
        return response()->json(['message' => 'image deleted successfully'], Response::HTTP_OK);
    }

    public function deleteMany(Request $request)
    {
        $serviceImage = ServiceImage::whereIn('id', $request->ids)->delete();

        return response()->json(['message' => 'images deleted successfully'], Response::HTTP_OK);
    }

    public function getAllForOneService($id)
    {
        $serviceImages = ServiceImage::where('service_id', $id)->get();
        return response()->json($serviceImages, Response::HTTP_OK);
    }
}
