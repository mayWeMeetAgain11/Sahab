<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function login(Request $request)
    {
        $manager = Manager::where('email', $request->email)
        ->where('password', $request->password)
        ->first();

        if ($manager) {
            $token = $manager->createToken('admin-token')->plainTextToken;

            return response()->json(['token' => $token, 'message' => $manager], 200);
        }

        return response()->json(['message' => 'not found'], 404);
    }
    public function store(Request $request)
    {

        $manager = new Manager();
        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->password = $request->password;
        $manager->save();

        return response()->json(['message' => $manager], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $manager = Manager::findOrFail($id);

        if (!$manager) {
            return response()->json(['message' => 'manager not found'], Response::HTTP_NOT_FOUND);
        }

        $manager->name = $request->name ?? $manager->name;
        $manager->email = $request->email ?? $manager->email;
        $manager->password = $request->password ?? $manager->password;
        $manager->save();
        return response()->json(['message' => $manager], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $manager = Manager::findOrFail($id);

        if (!$manager) {
            return response()->json(['message' => 'manager not found'], Response::HTTP_NOT_FOUND);
        }

        $manager->delete();
        return response()->json(['message' => 'manager deleted successfully'], Response::HTTP_OK);
    }

    public function index()
    {
        $managers = Manager::all();
        return response()->json($managers, Response::HTTP_OK);
    }
}
