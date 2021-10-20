<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductMainImageStoreRequest;
use App\Http\Resources\Products\DefaultMediaResource;
use App\Models\Product\Product;
use App\Services\ProductImageService;
use Illuminate\Http\Request;

class MainImageController extends Controller
{
    /**
     * @param ProductImageService $productImageService
     */
    public function __construct(
        private ProductImageService $productImageService
    ) {}

    /**
     * @OA\Get(
     *     path="/products/{id}/main-image",
     *     tags={"Products"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID товара",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="product_id",
     *                         type="string",
     *                         description="ID в формате UUID"
     *                     ),
     *                     @OA\Property(
     *                         property="file_name",
     *                         type="string",
     *                         description="Полное название файла"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Название файла"
     *                     ),
     *                     @OA\Property(
     *                         property="mime_type",
     *                         type="string",
     *                         description="Mime-Type"
     *                     ),
     *                     @OA\Property(
     *                         property="size",
     *                         type="string",
     *                         description="Размер в байтах"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         description="Дата создания"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         description="Дата последнего редактирования"
     *                     ),
     *                     @OA\Property(
     *                         property="url",
     *                         type="string",
     *                         description="Ссылка на фото"
     *                     ),
     *                     example={"data":{
     *                          "product_id": "5524e4ca-8655-41b8-9d79-b9fdbc7d39f4",
     *                          "file_name": "gopher.png",
     *                          "name": "gopher",
     *                          "mime_type": "image/png",
     *                          "size": "38456",
     *                          "created_at": "2021-07-30T15:29:31.000000Z",
     *                          "updated_at": "2021-07-30T15:29:31.000000Z",
     *                          "url": "http://smaki.local/storage/29/goku.jpg",
     *                      }
     *                    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @return DefaultMediaResource
     */
    public function index(Product $product)
    {
        return new DefaultMediaResource(
            $product->getFirstMedia()
        );
    }

    /**
     * @OA\Post(
     *     path="/products/{id}/main-image",
     *     tags={"Products"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID товара",
     *         required=true
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="image",
     *                     description="Форматы: jpg, jpeg, png. Размер до 5МБ",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="product_id",
     *                         type="string",
     *                         description="ID в формате UUID"
     *                     ),
     *                     @OA\Property(
     *                         property="file_name",
     *                         type="string",
     *                         description="Полное название файла"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Название файла"
     *                     ),
     *                     @OA\Property(
     *                         property="mime_type",
     *                         type="string",
     *                         description="Mime-Type"
     *                     ),
     *                     @OA\Property(
     *                         property="size",
     *                         type="string",
     *                         description="Размер в байтах"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         description="Дата создания"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         description="Дата последнего редактирования"
     *                     ),
     *                     @OA\Property(
     *                         property="url",
     *                         type="string",
     *                         description="Ссылка на фото"
     *                     ),
     *                     example={"data":{
     *                          "product_id": "5524e4ca-8655-41b8-9d79-b9fdbc7d39f4",
     *                          "file_name": "gopher.png",
     *                          "name": "gopher",
     *                          "mime_type": "image/png",
     *                          "size": "38456",
     *                          "created_at": "2021-07-30T15:29:31.000000Z",
     *                          "updated_at": "2021-07-30T15:29:31.000000Z",
     *                          "url": "http://smaki.local/storage/29/goku.jpg",
     *                      }
     *                    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param Product $product
     * @param Request $request
     * @return DefaultMediaResource
     */
    public function store(Product $product, ProductMainImageStoreRequest $request)
    {
        return new DefaultMediaResource(
            $this->productImageService->storeMainImage($product)
        );
    }
}
