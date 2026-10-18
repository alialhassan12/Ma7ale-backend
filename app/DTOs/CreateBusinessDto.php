<?php

namespace App\DTOs;

use Ramsey\Uuid\Uuid;

class CreateBusinessDto
{
    public function __construct(
        public readonly string $owner_id,
        public readonly string $name,
        public readonly string $business_category_id,
        public readonly string $slug_store,
        public readonly string $country_code,
        public readonly string $phone_number,
        public readonly string $order_method,
    )
    {}
}