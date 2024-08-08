<?php

namespace App\Entity;

use App\Service\Catalog\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Cart implements \App\Service\Cart\Cart
{
    public const CAPACITY = 3;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\OneToMany(targetEntity: 'CartItem', mappedBy: 'cart', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $cartItems;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getTotalPrice(): int
    {
        return array_reduce(
            $this->cartItems->toArray(),
            static fn(int $total, CartItem $item): int => $total + $item->getProduct()->getPrice() * $item->getQuantity(),
            0
        );
    }

    #[Pure]
    public function isFull(): bool
    {
        $itemsInCart = array_reduce(
            $this->cartItems->toArray(),
            static fn(int $itemsInCart, CartItem $cartItem): int => $itemsInCart + $cartItem->getQuantity(),
            0
        );

        return $itemsInCart >= self::CAPACITY;
    }

    public function getProducts(): iterable
    {
        return $this->cartItems->getIterator();
    }

    #[Pure]
    public function hasProduct(\App\Entity\Product $product): bool
    {
        foreach ($this->cartItems as $item) {
            if ($item->getProduct() === $product) {
                return true;
            }
        }
        return false;
    }

    public function addProduct(\App\Entity\Product $product): void
    {
        foreach ($this->cartItems as $item) {

            if ($item->getProduct() == $product) {
                $item->increaseQuantity();
                return;
            }
        }
        $this->cartItems->add(new CartItem($this, $product));
    }

    public function removeProduct(\App\Entity\Product $product): void
    {
        foreach ($this->cartItems as $item) {
            if ($item->getProduct() === $product) {
                $this->cartItems->removeElement($item);
                return;
            }
        }
    }

    public function decreaseProductQuantity(\App\Entity\Product $product, int $amount = 1): void
    {
        foreach ($this->cartItems as $item) {
            if ($item->getProduct() === $product) {
                $item->decreaseQuantity($amount);
                if ($item->getQuantity() <= 0) {
                    $this->cartItems->removeElement($item);
                }
                return;
            }
        }
    }
}