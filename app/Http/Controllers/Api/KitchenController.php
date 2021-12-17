<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultCollection;
use App\Models\Kitchen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KitchenController extends Controller
{
    /**
     * @OA\Get(
     *     path="/kitchens",
     *     tags={"Kitchens"},
     *     security={{"Bearer":{}}},
     *     summary="Список физических кухонь",
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
     *                         property="code",
     *                         type="string",
     *                         description="Текстовый идентификатор кухни"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Название кухни"
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
     *                          {
     *                              "id": 1,
     *                              "code": "kulparkivska",
     *                              "title": "Кульпарківська",
     *                              "created_at": "2021-09-02T13:13:03.000000Z",
     *                              "updated_at": "2021-09-02T13:13:03.000000Z"
     *                          }
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
        return new DefaultCollection(
            Kitchen::get()
        );
    }
}
