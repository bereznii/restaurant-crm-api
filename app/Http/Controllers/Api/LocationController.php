<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationCollection;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/locations",
     *     tags={"Locations"},
     *     security={{"Bearer":{}}},
     *     summary="Список доставочных терминалов из iiko для курьеров",
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
     *                         property="delivery_terminal_id",
     *                         type="string",
     *                         description="Уникальный идентификатор доставочного терминала в iiko"
     *                     ),
     *                     @OA\Property(
     *                         property="restaurant",
     *                         type="string",
     *                         description="Идентификатор ресторана"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Название точки"
     *                     ),
     *                     @OA\Property(
     *                         property="city_sync_id",
     *                         type="string",
     *                         description="Текстовый идентификатор города"
     *                     ),
     *                     @OA\Property(
     *                         property="city",
     *                         type="string",
     *                         description="Город"
     *                     ),
     *                     @OA\Property(
     *                         property="street",
     *                         type="string",
     *                         description="Улица"
     *                     ),
     *                     @OA\Property(
     *                         property="street_ua",
     *                         type="string",
     *                         description="Улица на украинском"
     *                     ),
     *                     @OA\Property(
     *                         property="house_number",
     *                         type="string",
     *                         description="Номер дома"
     *                     ),
     *                     @OA\Property(
     *                         property="latitude",
     *                         type="string",
     *                         description="Широта"
     *                     ),
     *                     @OA\Property(
     *                         property="longitude",
     *                         type="string",
     *                         description="Долгота"
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
     *                              "delivery_terminal_id": "aa15c7b2-768f-dbf1-016c-8fc96e6aa61b",
     *                              "restaurant": "smaki",
     *                              "name": "Кульпарковская Смаки",
     *                              "city_sync_id": "lviv",
     *                              "city": "Львов",
     *                              "street": "улица Кульпарковская",
     *                              "street_ua": "вулиця Кульпарківська",
     *                              "house_number": "95",
     *                              "latitude": null,
     *                              "longitude": null,
     *                              "created_at": "2021-07-28T11:08:01.000000Z",
     *                              "updated_at": "2021-07-28T11:08:01.000000Z"
     *                          }
     *                     }}
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @return LocationCollection
     */
    public function index(Request $request)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getMethod() . ' ' . $request->getRequestUri());

        return new LocationCollection(Location::with('city')->get());
    }
}
