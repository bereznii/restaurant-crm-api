<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultCollection;
use App\Http\Resources\DefaultMediaCollection;
use App\Models\Product;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return DefaultMediaCollection
     */
    public function index(Product $product)
    {
        return new DefaultMediaCollection(
            $product->getMedia()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Product $product
     * @param Request $request
     * @return DefaultMediaCollection
     */
    public function store(Product $product, Request $request)
    {
        return new DefaultMediaCollection(
            $product->getMedia()
        );
    }
}
