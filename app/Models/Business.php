<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'owner_id',
    'business_category_id',
    'name',
    'slug_store',
    'description',
    'phone_number',
    'country_code',
    'logo',
    'logo_url',
    'order_method'
])]
class Business extends Model
{
    use HasUuids;

    // relations
    public function category()
    {
        return $this->belongsTo(BusinessCategory::class,'business_category_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id');
    }
}
