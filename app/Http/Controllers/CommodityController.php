<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommodityController extends Controller
{
    // List all commodities
    // public function index()
    // {
    //     $commodities = Commodity::all();
    //     return response()->json($commodities, 200);
    // }

    // // View a single commodity
    // public function show($id)
    // {
    //     $commodity = Commodity::findOrFail($id);
    //     return response()->json($commodity, 200);
    // }
    public function index()
    {
        $commodities = Commodity::all()->map(function($commodity) {
            $commodity->image_url = $commodity->image ? Storage::url($commodity->image) : null;
            return $commodity;
        });

        return response()->json($commodities, 200);
    }

    public function show($id)
    {
        $commodity = Commodity::findOrFail($id);
        $commodity->image_url = $commodity->image ? Storage::url($commodity->image) : null;

        return response()->json($commodity, 200);
    }

    // Create a new commodity
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'required|string|max:755',
    //         'price' => 'required|numeric|min:0',
    //     ]);

    //     $commodity = Commodity::create([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'price' => $request->price,
    //     ]);

    //     return response()->json([
    //         'message' => 'Commodity created successfully',
    //         'commodity' => $commodity,
    //     ], 201);
    // }
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:755',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validate image
            ]);

            // Handle the image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('commodities', 'public');
            }

            $commodity = Commodity::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $imagePath ?? null,  // Save the image path
            ]);

            return response()->json([
                'message' => 'Commodity created successfully',
                'commodity' => $commodity,
            ], 201);
        }


    // Update an existing commodity
    // public function update(Request $request, $id)
    // {
    //     $commodity = Commodity::findOrFail($id);

    //     $request->validate([
    //         'name' => 'sometimes|string|max:255',
    //         'description' => 'sometimes|string|max:755',
    //         'price' => 'sometimes|numeric|min:0',
    //     ]);

    //     $commodity->update($request->only(['name', 'description', 'price']));

    //     return response()->json([
    //         'message' => 'Commodity updated successfully',
    //         'commodity' => $commodity,
    //     ], 200);
    // }
        public function update(Request $request, $id)
        {
            $commodity = Commodity::findOrFail($id);

            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string|max:755',
                'price' => 'sometimes|numeric|min:0',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validate image
            ]);

            // Handle image upload and replace the existing image if needed
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('commodities', 'public');
                $commodity->image = $imagePath;
            }

            $commodity->update($request->only(['name', 'description', 'price', 'image']));

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
