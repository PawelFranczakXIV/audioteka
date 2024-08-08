<?php

namespace App\Service\Catalog;

interface ProductService
{
    public function add(string $name, int $price): Product;

    public function edit(\App\Entity\Product $product, ?string $name, ?int $price): \App\Entity\Product;

    public function remove(string $id): void;
}