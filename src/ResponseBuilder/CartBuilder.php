<?php

namespace App\ResponseBuilder;

use App\Service\Cart\Cart;

class CartBuilder
{
    public function __invoke(Cart $cart): array
    {
        $data = [
            'total_price' => $cart->getTotalPrice(),
            'products' => []
        ];

        foreach ($cart->getProducts() as $cartItem) {
            $product = $cartItem->getProduct();
            $data['products'][] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $cartItem->getQuantity(),
                'total_item_price' => $product->getPrice() * $cartItem->getQuantity()
            ];
        }

        return $data;
    }
}
