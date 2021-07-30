<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productPrototypes = [
            [
                'name' => 'Текка макі',
                'price' => 70,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Саке макі',
                'price' => 75,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Кіото макі',
                'price' => 85,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Каппа макі',
                'price' => 92,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Міядзакі макі',
                'price' => 79,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Каліфорнія з тунцем',
                'price' => 165,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Каліфорнія з вугрем',
                'price' => 170,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Каліфорнія з лососем',
                'price' => 180,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Саб-зіро макі',
                'price' => 95,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Дакота макі',
                'price' => 110,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Ясай макі',
                'price' => 105,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Ітабасі макі',
                'price' => 104,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Авокадо макі',
                'price' => 98,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Каліфорнія тобіко макі',
                'price' => 98,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Унагі макі',
                'price' => 85,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Хотатегай нігірі',
                'price' => 40,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Саке гурме нігірі',
                'price' => 37,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Пицца Маргарита',
                'price' => 93,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Пицца с копченой курицей',
                'price' => 99,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Пицца с ветчиной и грибами',
                'price' => 99,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Пицца с охотничьими колбасками',
                'price' => 102,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Пицца сырная с беконом',
                'price' => 107,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Пицца 4 Сыра',
                'price' => 120,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Пицца Наполи',
                'price' => 111,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Пицца BBQ',
                'price' => 135,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Пицца Бургер',
                'price' => 146,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Мисо суп с копченой курицей',
                'price' => 77,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Мисо суп с тофу',
                'price' => 89,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Мисо суп с угрем',
                'price' => 134,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Bonaqua негазированная',
                'price' => 19,
                'weight_type' => 'л'
            ],
            [
                'name' => 'Coca-Cola 0.5 л',
                'price' => 25,
                'weight_type' => 'л'
            ],
            [
                'name' => 'Coca-Cola 1 л',
                'price' => 44,
                'weight_type' => 'л'
            ],
            [
                'name' => 'Fuze tea 1 л',
                'price' => 39,
                'weight_type' => 'л'
            ],
            [
                'name' => 'Сок Rich (Яблоко)',
                'price' => 47,
                'weight_type' => 'л'
            ],
            [
                'name' => 'Сок Rich (Апельсин)',
                'price' => 47,
                'weight_type' => 'л'
            ],
            [
                'name' => 'Сок Rich (Вишня)',
                'price' => 47,
                'weight_type' => 'л'
            ],
            [
                'name' => 'Салат с копченой курицей',
                'price' => 82,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Салат хияши вакаме',
                'price' => 91,
                'weight_type' => 'г'
            ],
            [
                'name' => 'Салат Цезарь',
                'price' => 147,
                'weight_type' => 'г'
            ],
        ];

        $restaurants = [
            'smaki',
            'go'
        ];

        $cities = [
            'lviv',
            'mykolaiv',
            'sumy',
            'ivano-frankivsk',
            'khmelnytskyi',
            'kherson',
            'rivne',
            'lutsk',
            'vinnytsia',
            'ternopil',
        ];

        DB::table('products')->truncate();

        $date = date('Y-m-d H:i:s');
        foreach ($restaurants as $restaurant) {
            foreach ($cities as $city) {
                foreach ($productPrototypes as $productPrototype) {
                    $productsToInsert[] = [
                        'restaurant' => $restaurant,
                        'city_sync_id' => $city,
                        'article' => 'art-' . rand(1, 100000),
                        'title_ua' => $productPrototype['name'],
                        'title_ru' => $productPrototype['name'],
                        'is_active' => (bool) rand(1, 0),
                        'price' => $productPrototype['price'],
                        'price_old' => $productPrototype['price'] * 0.95,
                        'weight' => 250,
                        'weight_type' => $productPrototype['weight_type'],
                        'description_ua' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse blandit hendrerit mi eget aliquam. Mauris volutpat sem augue, quis tincidunt leo vehicula ut. Pellentesque convallis vel eros vitae luctus. Curabitur commodo fringilla risus, consectetur varius enim porttitor vel. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus magna sem, efficitur quis interdum quis, viverra in purus. Sed finibus odio euismod felis gravida mollis. Praesent ante sapien, ornare sed consectetur at, euismod eget nulla. Suspendisse accumsan imperdiet sagittis. Maecenas quis mi tortor. Integer vel ante ut nisi pulvinar tincidunt id sed metus. Nunc non erat ut tortor finibus gravida quis quis magna. Suspendisse varius odio sed leo scelerisque pretium. Praesent auctor odio sit amet leo posuere, nec tempus risus tincidunt.',
                        'description_ru' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse blandit hendrerit mi eget aliquam. Mauris volutpat sem augue, quis tincidunt leo vehicula ut. Pellentesque convallis vel eros vitae luctus. Curabitur commodo fringilla risus, consectetur varius enim porttitor vel. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus magna sem, efficitur quis interdum quis, viverra in purus. Sed finibus odio euismod felis gravida mollis. Praesent ante sapien, ornare sed consectetur at, euismod eget nulla. Suspendisse accumsan imperdiet sagittis. Maecenas quis mi tortor. Integer vel ante ut nisi pulvinar tincidunt id sed metus. Nunc non erat ut tortor finibus gravida quis quis magna. Suspendisse varius odio sed leo scelerisque pretium. Praesent auctor odio sit amet leo posuere, nec tempus risus tincidunt.',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }
            }
        }

        DB::table('products')->insert($productsToInsert);
    }
}
