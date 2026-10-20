<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CreateBusinessDto;
use App\Http\Controllers\Controller;
use App\Services\BusinessService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class BusinessController extends Controller
{
    public function __construct(
        private BusinessService $businessService
    ){}

    public function createBusiness(Request $request){
        $validated=$request->validate([
            "name"=>['required','string','max:255'],
            "business_category_id"=>["required","exists:business_categories,id"],
            "slug_store"=>["required","unique:businesses,slug_store"],
            "country_code"=>['required',"string", "regex:/^\+[1-9]\d{0,3}$/"],
            "phone_number"=>[
                'required',
                'string',
                function($attribute,$value,$fail) use($request){
                    try{
                        $phoneUtil=PhoneNumberUtil::getInstance();
                        $phone=$phoneUtil->parse(
                            $request->input('country_code') . $value,null
                        );
                        if(!$phoneUtil->isValidNumber($phone)){
                            $fail("Please enter a valid phone number.");
                        }
                    }catch(NumberParseException $e){
                        $fail("Please enter a valid phone number.");
                    }
                }
            ],
            "order_method"=>['required',Rule::in(["whatsapp","instagram"])],
        ]);

        $user=$request->user();
        $owner_id=$user->id;

        $dto=new CreateBusinessDto(
            $owner_id,
            $validated['name'],
            $validated['business_category_id'],
            $validated['slug_store'],
            $validated['country_code'],
            $validated['phone_number'],
            $validated['order_method'],
        );

        $result=$this->businessService->createBusiness($dto);
        
        if(!$result['success']){
            return response()->json([
                "success"=>false,
                "message"=>$result['message']
            ],500);
        }

        // Mark onboarding as completed if not already completed
        if(!$user->onboarding_completed){
            $user->onboarding_completed=true;
            $user->save();
        }
        
        return response()->json([
            "success"=>true,
            "message"=>$result['message'],
            "business"=>$result['data']
        ],201);
    }

    public function getBusinesses(Request $request){
        $user=$request->user();

        $result=$this->businessService->getBusinesses($user->id);

        if(!$result['success']){
            return response()->json([
                "success"=>false,
                "message"=>$result['message']
            ],500);
        }

        return response()->json([
            "success"=>true,
            "message"=>$result['message'],
            "businesses"=>$result['data']
        ]);
    }
}
