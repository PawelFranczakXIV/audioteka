<?php

namespace App\Controller\Catalog;

use App\Entity\Product;
use App\Messenger\AddProductToCatalog;
use App\Messenger\EditProduct;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products/{id}", methods={"PATCH"}, name="product-edit")
 */
class EditController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(private ErrorBuilder $errorBuilder) { }

    public function __invoke(Product $product, Request $request): Response
    {
        $name = $request->get('name');
        $price = $request->get('price');

        if ((isset($name) && $name === '') || (isset($price) && $price < 1)) { //przepraszam wiem że można zrobić jakiś ładny walidator i podpiąć go tutaj i do create
            return new JsonResponse(
                $this->errorBuilder->__invoke('Invalid name or price.'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->dispatch(new EditProduct($product, $name, $price));

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}