<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CenterController extends Controller
{
    public function showAll(){
        $centers = Center::all();
        return response()->json($centers);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $center = new Center();
        $center->name = $request->name;
        $center->address = $request->address;
        $center->save();
        return response()->json(['Center' => $center], 201);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $center = Center::find($id);
        if (!$center) {
            return response()->json(['message' => 'Centro no encontrado'], 404);
        }

        $center->name = $request->name;
        $center->address = $request->address;
        $center->save();
        return response()->json(['Center' => $center]);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $center = Center::find($id);

        if (!$center) {
            return response()->json(['message' => 'Centro no encontrado'], 404);
        }

        $center->delete();

        return response()->json(['message' => 'Centro eliminado correctamente']);
    }

    public function show($id): \Illuminate\Http\JsonResponse{
        $center = Center::find($id);
        return response()->json($center);
    }
}
