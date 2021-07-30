<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Rbac\RolesCollection;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/rbac/roles",
     *     tags={"RBAC"},
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
     *                         property="title",
     *                         type="string",
     *                         description="Название"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="Идентификатор"
     *                     ),
     *                     example={"data":{
     *                          {
     *                              "id": 2,
     *                              "title": "Контент-менеджер",
     *                              "name": "content_manager"
     *                          }
     *                     }}
     *                 )
     *             )
     *         }
     *     ),
     * )
     *
     * @return RolesCollection
     */
    public function index()
    {
        return new RolesCollection(Role::select('id', 'title', 'name')->get());
    }
}
