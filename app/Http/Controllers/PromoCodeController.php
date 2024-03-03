<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePromoCodeRequest;
use App\Http\Requests\UpdatePromoCodeRequest;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\PromoCode;

class PromoCodeController extends Controller
{
    public function store(Request $request)
    {

        $promoCode = new PromoCode();

        $promoCode->discount = $request->discount;
        // $promoCode->booking_id = $request->booking_id;

        $promoCode->save();

        return response()->json(['message' => 'promo code added successfully'], Response::HTTP_OK);
    }

    public function storeMany(CreatePromoCodeRequest $request)
{

    $promoCodesToInsert = [];

    for ($i = 0; $i < $request->numberOfIteration; $i++) {
        $promoCodesToInsert[] = [
            'discount' => $request->discount,
            'booking_id' => $request->booking_id,
        ];
    }

    PromoCode::insert($promoCodesToInsert);

    return response()->json(['message' => 'promo codes added successfully'], Response::HTTP_OK);
}

    public function update(Request $request, $id)
    {
        $promoCode = PromoCode::findOrFail($id);

        if (!$promoCode) {
            return response()->json(['message' => 'promoCode not found'], Response::HTTP_NOT_FOUND);
        }

        $promoCode->discount = $request->discount || $request->discount;
        $promoCode->booking_id = $request->booking_id ?? $promoCode->booking_id;

        $promoCode->save();

        return response()->json(['message' => 'promo code updated successfully'], Response::HTTP_OK);
    }

    public function apply(Request $request, $id)
    {
        $promoCode = PromoCode::findOrFail($id);

        if (!$promoCode) {
            return response()->json(['message' => 'promoCode not found'], Response::HTTP_NOT_FOUND);
        }

        if ($promoCode->booking_id) {
            return response()->json(['message' => 'promoCode has activated before'], Response::HTTP_NOT_FOUND);
        }

        $promoCode->booking_id = $request->booking_id ?? $promoCode->booking_id;

        $promoCode->save();

        return response()->json(['message' => 'promo code applied successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $promoCode = PromoCode::findOrFail($id);

        if (!$promoCode) {
            return response()->json(['message' => 'promo code not found'], Response::HTTP_NOT_FOUND);
        }

        $promoCode->delete();
        return response()->json(['message' => 'promo code deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $promoCode = PromoCode::findOrFail($id);

        if (!$promoCode) {
            return response()->json(['message' => 'promo code not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($promoCode, Response::HTTP_OK);
    }

    public function index()
    {
        $promoCodes = PromoCode::leftJoin();
        return response()->json($promoCodes, Response::HTTP_OK);
    }
}
