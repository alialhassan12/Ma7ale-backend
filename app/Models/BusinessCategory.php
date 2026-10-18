<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'icon', 'is_active'])]
class BusinessCategory extends Model
{
    use HasUuids;

    // relations
    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}
