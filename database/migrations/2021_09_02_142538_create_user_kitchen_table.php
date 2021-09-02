<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserKitchenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kitchen_code')->nullable()->after('status');
        });

        $kitchens = [
            [
                'kitchen_code' => 'kulparkivska',
                'kitchen_title' => 'Кульпарківська',
            ],
            [
                'kitchen_code' => 'mazepi',
                'kitchen_title' => 'Мазепи',
            ],
            [
                'kitchen_code' => 'sadybna',
                'kitchen_title' => 'Садибна',
            ],
            [
                'kitchen_code' => 'doroshenko',
                'kitchen_title' => 'Дорошенко',
            ],
            [
                'kitchen_code' => 'shyroka',
                'kitchen_title' => 'Широка',
            ],
            [
                'kitchen_code' => 'mykolaiv',
                'kitchen_title' => 'Миколаїв',
            ],
            [
                'kitchen_code' => 'sumy',
                'kitchen_title' => 'Суми',
            ],
            [
                'kitchen_code' => 'ivano-frankivsk',
                'kitchen_title' => 'Івано-Франківськ',
            ],
            [
                'kitchen_code' => 'khmelnytskyi',
                'kitchen_title' => 'Хмельницький',
            ],
            [
                'kitchen_code' => 'kherson',
                'kitchen_title' => 'Херсон',
            ],
            [
                'kitchen_code' => 'rivne',
                'kitchen_title' => 'Рівне',
            ],
            [
                'kitchen_code' => 'lutsk',
                'kitchen_title' => 'Луцьк',
            ],
            [
                'kitchen_code' => 'vinnytsia',
                'kitchen_title' => 'Вінниця',
            ],
            [
                'kitchen_code' => 'ternopil',
                'kitchen_title' => 'Тернопіль',
            ],
        ];

        Schema::create('kitchens', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->timestamps();
        });

        $date = date('Y-m-d H:i:s');
        DB::table('kitchens')->insert(array_map(function ($item) use ($date) {
            return [
                'code' => $item['kitchen_code'],
                'title' => $item['kitchen_title'],
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }, $kitchens));

        $kitchensWithCorrespondent = [
            'Smaki Maki Кульпарківська' => 'kulparkivska',
            'Smaki Maki Мазепи' => 'mazepi',
            'Smaki Maki Садибна' => 'sadybna',
            'Smaki Maki Дорошенко' => 'doroshenko',
            'Smaki Maki Широка' => 'shyroka',
            'Smaki Maki Миколаїв' => 'mykolaiv',
            'Smaki Maki Суми' => 'sumy',
            'Smaki Maki Івано-Франківськ' => 'ivano-frankivsk',
            'Smaki Maki Хмельницький' => 'khmelnytskyi',
            'Smaki Maki Херсон' => 'kherson',
            'Smaki Maki Рівне' => 'rivne',
            'Smaki Maki Луцьк' => 'lutsk',
            'Smaki Maki Вінниця' => 'vinnytsia',
            'Smaki Maki Тернопіль' => 'ternopil',
        ];

        $existingUsers = \App\Models\User::with(['locations' => function ($query) {
            $query->where('locations.name', 'like', '%Smaki%');
        }])->get();
        foreach ($existingUsers as $user) {
            if ($user->locations->count() > 0) {
                $locationName = $user->locations[0]->name;
                $kitchenNameToStore = $kitchensWithCorrespondent[$locationName];

                $user->kitchen_code = $kitchenNameToStore;
                $user->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kitchen_code');
        });

        Schema::dropIfExists('kitchens');
    }
}
