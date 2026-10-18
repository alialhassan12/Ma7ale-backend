<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;

class BusinessCategoriesController extends Controller
{
    public function addBusinessCategory(Request $request){
        $validated=$request->validate([
            'name'=>['required','string','max:255'],
            'icon'=>['required','string','max:255']
        ]);

        $category=BusinessCategory::create($validated);

        return response()->json([
            "message"=>"Category added successfully",
            "category"=>$category
        ],201);
    }
}
