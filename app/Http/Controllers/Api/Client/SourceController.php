<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultCollection;
use App\Models\Client\Client;

class SourceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/sources",
     *     tags={"Clients"},
     *     security={{"Bearer":{}}},
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Тип источника"
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="integer",
     *                         description="Название источника"
     *                     ),
     *                     example={"data":{
     *                          {
     *                              "name": "website",
     *                              "title": "Сайт",
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
    public function index()
    {
        return new DefaultCollection(
            collect(Client::CLIENT_SOURCES)
        );
    }
}
