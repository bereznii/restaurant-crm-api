<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Me\UpdateCoordinatesRequest;
use App\Http\Resources\DefaultResource;
use App\Models\UserCoordinate;
use App\Services\Mobile\MeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MeController extends Controller
{
    /**
     * @param MeService $meService
     */
    public function __construct(
        private MeService $meService
    ) {}

    /**
     * @OA\Put(
     *     path="/mobile/me/coordinates",
     *     tags={"Mobile.Me"},
     *     description="Обновить свои координаты. Доступ для пользователей с ролью: <b>Courier</b>",
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *               @OA\Property(
     *                  property="latitude",
     *                  type="string",
     *                  description="Широта"
     *               ),
     *               @OA\Property(
     *                  property="longitude",
     *                  type="string",
     *                  description="Долгота"
     *               )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         type="object",
     *                         @OA\Property(
     *                             property="success",
     *                             type="boolean"
     *                         )
     *                     ),
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @param UpdateCoordinatesRequest $request
     * @return DefaultResource
     */
    public function updateCoordinates(UpdateCoordinatesRequest $request)
    {
        Log::channel('mobile')->info(Auth::id() . ' | ' . $request->getRequestUri() . ' : ' . json_encode($request->validated()));

        return new DefaultResource([
            'success' => $this->meService->updateCoordinates($request->validated()),
        ]);
    }
}
