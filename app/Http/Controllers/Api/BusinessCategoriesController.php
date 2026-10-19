<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function getBusinessCategories(){
        try{
            $categories=BusinessCategory::all();
            return response()->json([
                "success"=>true,
                "message"=>"Business categories retrieved successfully",
                "categories"=>$categories
            ],200);
        }catch(Exception $e){
            Log::error('Business categories error: ' . $e->getMessage());
            return response()->json([
                "success"=>false,
                "message"=>"Failed to get business categories"
            ],500);
        }
    }
}
