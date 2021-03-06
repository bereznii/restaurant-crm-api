<?php

namespace App\Services;

use App\Models\Product\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductImageService
{
    /**
     * @param Product $product
     * @return Media
     */
    public function storeMainImage(Product $product): Media
    {
        try {
            $product->clearMediaCollection();
            $product->addMediaFromRequest('image')->toMediaCollection();
        } catch (\Exception $e) {
            Log::error(Auth::id() . ' | ' . $e->getMessage());
        }

        return $product->getFirstMedia();
    }
}
