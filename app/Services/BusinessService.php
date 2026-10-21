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

    public function getBusinesses($user_id,$filter,$search){
        try{
            // get owned and member businesses
            $query=Business::query()
                ->with([
                    'category',
                    'members'=>function($query) use($user_id){
                        $query->where('users.id', $user_id);
                    }
                ])
                ->where(function ($query) use($user_id,$filter,$search) {
                    // $query->where('owner_id',$user_id)
                    // filter by role
                    $query->when($filter, function ($query) use ($filter,$user_id,$search) {
                        if($filter=== 'all'){
                            $query->where(function($q) use ($user_id) {
                                $q->where('owner_id',$user_id)
                                ->orWhereHas('members', function ($q) use ($user_id) {
                                    $q->where('users.id', $user_id);
                                });
                            })
                            ->when($search, function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
                                ->orWhere('slug_store', 'like', "%$search%");
                            });
                        }else if($filter === 'owner') {
                            $query->where('owner_id', $user_id)
                                ->when($search, function ($query) use ($search) {
                                    $query->where('name', 'like', "%$search%")
                                    ->orWhere('slug_store', 'like', "%$search%");
                                });
                        } else if($filter === 'member') {
                            // $query->where('owner_id', '!=', $user_id)
                                $query->whereHas('members', function ($q) use ($user_id,$search) {
                                    $q->where('users.id', $user_id)
                                    ->when($search, function ($query) use ($search) {
                                        $query->where('name', 'like', "%$search%")
                                        ->orWhere('slug_store', 'like', "%$search%");
                                    });
                                });
                        }
                    });
                })->paginate(5);

            $data=$query->toArray();

            $businesses=$query->getCollection()->transform(function($business) use ($user_id){
                $business->user_role=$business->owner_id == $user_id 
                    ? 'owner' 
                    : $business->members()->first()?->pivot->role;
                unset($business->members);
                return $business;
            });

            $data['data']=$businesses;

            return [
                "success"=>true,
                "message"=>"Businesses fetched successfully",
                "data"=>$data
            ];
        }catch(Exception $e){
            Log::error('Failed to fetch businesses: ' . $e->getMessage());
            return [
                "success"=>false,
                "message"=>"Failed to fetch businesses"
            ];
        }
    }
}
