<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationCollection;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/locations",
     *     tags={"Locations"},
     *     security={{"Bearer":{}}},
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
     *                         property="restaurant",
     *                         type="string",
     *                         description="Код ресторана"
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
     *                              "restaurant": "smaki",
     *                              "name": "Кульпарковская Смаки",
     *                              "city_sync_id": "lviv",
     *                              "city": "Львов",
     *                              "street": "ул. Кульпарковская",
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
    public function index()
    {
        return new LocationCollection(Location::with('city')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
