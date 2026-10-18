<?php

namespace App\Services;

use App\DTOs\CreateBusinessDto;
use App\Models\Business;
use Exception;
use Illuminate\Support\Facades\Log;

class BusinessService
{
    public function createBusiness(CreateBusinessDto $dto)
    {
        try{
            $business = Business::create([
                'owner_id' => $dto->owner_id,
                'name' => $dto->name,
                'business_category_id' => $dto->business_category_id,
                'slug_store' => $dto->slug_store,
                'country_code' => $dto->country_code,
                'phone_number' => $dto->phone_number,
                'order_method' => $dto->order_method,
            ]);

            if(!$business){
                return [
                    "success"=>false,
                    "message"=>"Failed to create business"
                ];
            }

            return [
                "success"=>true,
                "message"=>"Business created successfully",
                "data"=>$business
            ];
        }catch(Exception $e){
            Log::error('Business creation error: ' . $e->getMessage());
            return [
                "success"=>false,
                "message"=>"Failed to create business"
            ];
        }
    }
}
