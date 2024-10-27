<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommodityController extends Controller
{
   
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
//         public function store(Request $request)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'description' => 'nullable|string|max:755',
//         'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validate image
//         'price' => 'required|numeric|min:0',
//         'market_price' => 'nullable|numeric|min:0',
    
//         'category' => 'nullable|string|max:255',
//         'unit' => 'nullable|string|max:50|in:kilogram,ounce,pound', // Add more units if needed
//         'stock' => 'nullable|integer|min:0',
//         'origin_country' => 'nullable|string|max:255',
//         'supplier' => 'nullable|string|max:255',
//         'expiry_date' => 'nullable',
//         'rating' => 'nullable|numeric|min:0|max:5',
//         'reviews_count' => 'nullable|integer|min:0',
       
//     ]);

//     // // Handle the image upload
//     if ($request->hasFile('image')) {
//         $imagePath = $request->file('image')->store('commodities', 'public');
//     }

//     $commodity = Commodity::create([
//         'name' => $request->name,
//         'description' => $request->description,
//         'price' => (float) $request->price, // Cast to float
//         'market_price' => (float) $request->market_price ?? (float) $request->price, // Cast to float
//         'category' => $request->category,
//         'unit' => $request->unit ?? 'kilogram', // Default to kilogram
//         'stock' => $request->stock ?? 0,
//         'origin_country' => $request->origin_country,
//         'supplier' => $request->supplier,
//         'expiry_date' => $request->expiry_date,
//         'rating' => (float) $request->rating ?? 0, // Cast to float
//         'reviews_count' => $request->reviews_count ?? 0,
//         'image' => $imagePath ?? null,
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
        'description' => 'nullable|string|max:755',
        'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validate image
        'price' => 'required|numeric|min:0',
        'market_price' => 'nullable|numeric|min:0',
        'category' => 'nullable|string|max:255',
        
        // Match the units in your dropdown
        'unit' => 'nullable|string|max:50|in:kilogram,ounce,pound',  // Validate unit dropdown options
        
        'stock' => 'nullable|integer|min:0',
        'origin_country' => 'nullable|string|max:255',  // Validate country input
        'supplier' => 'nullable|string|max:255',
        'expiry_date' => 'nullable|date_format:Y-m-d',  // Use date format validation
        'rating' => 'nullable|numeric|min:0|max:5',
        'reviews_count' => 'nullable|integer|min:0',
    ]);

    // Handle the image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('commodities', 'public');
    }

    // Create commodity
    $commodity = Commodity::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => (float) $request->price,
        'market_price' => (float) $request->market_price ?? (float) $request->price,
        'category' => $request->category,
        'unit' => $request->unit ?? 'kilogram',  // Default unit if not provided
        'stock' => $request->stock ?? 0,
        'origin_country' => $request->origin_country,
        'supplier' => $request->supplier,
        'expiry_date' => $request->expiry_date,
        'rating' => (float) $request->rating ?? 0,
        'reviews_count' => $request->reviews_count ?? 0,
        'image' => $imagePath ?? null,
    ]);

    return response()->json([
        'message' => 'Commodity created successfully',
        'commodity' => $commodity,
    ], 201);
}

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
