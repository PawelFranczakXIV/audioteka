<?php

namespace App\Messenger;

use App\Entity\Product;

class EditProduct
{
    public function __construct(
        public readonly Product $product,
        public readonly ?string $name,
        public readonly ?int $price
    ) {}
}
