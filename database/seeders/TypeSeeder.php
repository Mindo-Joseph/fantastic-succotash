<?php
namespace Database\Seeders;
use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array(
            ['id' => 1,
                'title' => 'Product',
            ],
            ['id' => 2,
                'title' => 'Dispatcher',
            ],
            ['id' => 3,
                'title' => 'Vendor',
            ],
            ['id' => 4,
                'title' => 'Brand',
            ],
            ['id' => 5,
                'title' => 'Celebrity',
            ],
            ['id' => 6,
                'title' => 'Subcategory',
            ],
        );
        foreach ($types as $type) {
           Type::upsert($type, ['id', 'title']);
        }
    }
}
