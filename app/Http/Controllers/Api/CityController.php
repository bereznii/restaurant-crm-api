<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityCollection;
use App\Http\Resources\LocationCollection;
use App\Models\City;
use App\Models\Location;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/cities",
     *     tags={"Cities"},
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
     *                         property="sync_id",
     *                         type="string",
     *                         description="Текстовый идентификатор"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Название города"
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
     *                                  "sync_id":"lviv",
     *                                  "name": "Львов",
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
     * @return CityCollection
     */
    public function index()
    {
        return new CityCollection(City::get());
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
