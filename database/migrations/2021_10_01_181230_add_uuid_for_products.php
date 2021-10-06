<?php

use App\Models\City;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddUuidForProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_prices');

        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('restaurant');
            $table->string('article');
            $table->string('title_ua');
            $table->string('title_ru');
            $table->integer('weight')->nullable();
            $table->string('weight_type', 20)->nullable();
            $table->string('category_sync_id', 20)->nullable();
            $table->string('type_sync_id', 20)->nullable();
            $table->text('description_ua')->nullable();
            $table->text('description_ru')->nullable();
            $table->timestamps();
        });

        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->uuid('product_id');
            $table->string('city_sync_id');
            $table->integer('price');
            $table->integer('price_old')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
        Schema::table('product_prices', function (Blueprint $table) {
            $table->unique(['product_id', 'city_sync_id'], 'unique_price');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->string('uuid')->nullable()->after('id');
        });
        foreach ($this->getCitiesUuid() as $citySyncId => $cityUuid) {
            City::where('sync_id', $citySyncId)->update(['uuid' => $cityUuid]);
        }
        Schema::table('cities', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }

    /**
     * @return array
     */
    private function getCitiesUuid(): array
    {
        return [
            'lviv' => 'cbc41f7d-823c-411a-b6d2-9af963c6ed99',
            'mykolaiv' => '6cad0c8f-197b-4426-ae18-e77543f21da9',
            'sumy' => '914e5cea-d605-4bc3-ab82-3bccb83329c5',
            'ivano-frankivsk' => '54abacac-c31d-43f6-8a68-6bce5c3a694a',
            'khmelnytskyi' => 'b718f3ab-0f17-4c5d-b73c-ceb54dc84a14',
            'kherson' => 'fd751945-0241-4e03-a154-73f12bbc0ee8',
            'rivne' => 'ff1fd791-72c6-4157-9abc-cc27e5180a82',
            'lutsk' => 'c6ac964f-0c46-4ac1-9a80-4fba4d04b236',
            'vinnytsia' => 'b8b978d8-f6f2-47b8-b67f-f77b5c2cf92b',
            'ternopil' => '5524e4ca-8655-41b8-9d79-b9fdbc7d39f4',
        ];
    }
}
