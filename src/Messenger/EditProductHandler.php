<?php

namespace App\Messenger;

use App\Entity\Product;
use App\Service\Cart\Cart;
use App\Service\Cart\CartService;
use App\Service\Catalog\ProductService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EditProductHandler implements MessageHandlerInterface
{
    public function __construct(private ProductService $service) { }

    public function __invoke(EditProduct $command): Product
    {
        return $this->service->edit(
            $command->product,
            $command->name,
            $command->price
        );
    }
}
