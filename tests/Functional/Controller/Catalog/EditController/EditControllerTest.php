<?php

namespace App\Tests\Functional\Controller\Catalog\EditController;


use App\Tests\Functional\WebTestCase;

class EditControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideData
     */
    public function test_edit_product_name_price($old, $new, $expected): void
    {
        $this->client->request('POST', '/products', $old);
        $this->client->request('GET', '/products');

        $response = $this->getJsonResponse();
        $productToEditId = array_pop($response['products'])['id'];

        $editUrl = '/products/' . $productToEditId;
        $this->client->request('PATCH', $editUrl, $new);

        self::assertResponseStatusCodeSame($expected['code']);

        $this->client->request('GET', '/products');
        $response = $this->getJsonResponse();
        $productEdited = array_pop($response['products']);

        $this->assertEquals($productToEditId, $productEdited['id']);
        $this->assertEquals($expected['name'], $productEdited['name']);
        $this->assertEquals($expected['price'], $productEdited['price']);
    }

    public function provideData()
    {
        return [
            [
                [
                    'name' => 'oldName',
                    'price' => 200,
                ],
                [
                    'name' => 'newName',
                    'price' => 400,
                ],
                [
                    'code' => 204,
                    'name' => 'newName',
                    'price' => 400,
                ]
            ],
            [
                [
                    'name' => 'oldName',
                    'price' => 200,
                ],
                [
                    'name' => 'editOnlyName',
                ],
                [
                    'code' => 204,
                    'name' => 'editOnlyName',
                    'price' => 200,
                ]
            ],
            [
                [
                    'name' => 'oldName',
                    'price' => 200,
                ],
                [
                    'price' => 400,
                ],
                [
                    'code' => 204,
                    'name' => 'oldName',
                    'price' => 400,
                ]
            ],
            [
                [
                    'name' => 'oldName',
                    'price' => 200,
                ],
                [],
                [
                    'code' => 204,
                    'name' => 'oldName',
                    'price' => 200,
                ]
            ],
            [
                [
                    'name' => 'oldName',
                    'price' => 200,
                ],
                [
                    'name' => 'newName',
                    'price' => -1,
                ],
                [
                    'code' => 422,
                    'name' => 'oldName',
                    'price' => 200,
                ]
            ],
            [
                [
                    'name' => 'oldName',
                    'price' => 200,
                ],
                [
                    'name' => '',
                    'price' => 400,
                ],
                [
                    'code' => 422,
                    'name' => 'oldName',
                    'price' => 200,
                ]
            ],
        ];
    }
}