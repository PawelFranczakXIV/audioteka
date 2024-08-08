<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class CartItem
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: 'Cart', inversedBy: 'cartItems')]
    private Cart $cart;

    #[ORM\ManyToOne(targetEntity: 'Product')]
    private Product $product;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    public function __construct(Cart $cart, Product $product, int $quantity = 1)
    {
        $this->id = Uuid::uuid4();
        $this->cart = $cart;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function increaseQuantity(int $amount = 1): void
    {
        $this->quantity += $amount;
    }

    public function decreaseQuantity(int $amount = 1): void
    {
        $this->quantity -= $amount;
    }
}