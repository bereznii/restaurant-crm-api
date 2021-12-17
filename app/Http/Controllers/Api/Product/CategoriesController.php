<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultCollection;
use App\Models\Product\ProductCategories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/product-categories",
     *     tags={"Products.Categories"},
     *     security={{"Bearer":{}}},
     *     summary="Список категорий товаров для сайта",
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="string",
     *                         description="ID"
     *                     ),
     *                     @OA\Property(
     *                         property="sync_id",
     *                         type="string",
     *                         description="Текстовый идентификатор"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Название категории"
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
     *                     example={"data":{
     *                              {
     *                                  "id":1,
     *                                  "sync_id":"sushi",
     *                                  "name": "Суши",
     *                                  "created_at": "2021-07-28T11:08:01.000000Z",
     *                                  "updated_at": "2021-07-28T11:08:01.000000Z"
     *                              }
     *                      }}
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @return DefaultCollection
     */
    public function index(Request $request)
    {
        return new DefaultCollection(ProductCategories::get());
    }
}
