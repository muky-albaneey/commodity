<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use Illuminate\Http\Request;

class CommodityController extends Controller
{
    // List all commodities
    public function index()
    {
        $commodities = Commodity::all();
        return response()->json($commodities, 200);
    }

    // View a single commodity
    public function show($id)
    {
        $commodity = Commodity::findOrFail($id);
        return response()->json($commodity, 200);
    }

    // Create a new commodity
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:755',
            'price' => 'required|numeric|min:0',
        ]);

        $commodity = Commodity::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Commodity created successfully',
            'commodity' => $commodity,
        ], 201);
    }

    // Update an existing commodity
    public function update(Request $request, $id)
    {
        $commodity = Commodity::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:755',
            'price' => 'sometimes|numeric|min:0',
        ]);

        $commodity->update($request->only(['name', 'description', 'price']));

        return response()->json([
            'message' => 'Commodity updated successfully',
            'commodity' => $commodity,
        ], 200);
    }

    // Delete a commodity
    public function destroy($id)
    {
        $commodity = Commodity::findOrFail($id);
        $commodity->delete();

        return response()->json([
            'message' => 'Commodity deleted successfully',
        ], 200);
    }
}
