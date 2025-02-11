<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prevents the seed from running multiple times :

        if (Location::count() === 0) {
            Location::create([
                'name' => 'Xelon AG',
                'address' => 'Postrasse 15',
                'postal_code' => '6300',
                'city' => 'Zug',
                'canton' => 'ZG',
                'country_id' => 1,
                'is_published' => 1,
            ]);

            Location::create([
                'name' => 'Parkside Interactive AG',
                'address' => 'Güterstrasse 144',
                'postal_code' => '4053',
                'city' => 'Basel',
                'canton' => 'BS',
                'country_id' => 1,
                'is_published' => 1,
            ]);

            Location::create([
                'name' => 'Le Hub',
                'address' => 'Rue des Terreaux 7',
                'postal_code' => '2000',
                'city' => 'Neuchâtel',
                'canton' => 'NE',
                'country_id' => 1,
                'is_published' => 1,
            ]);

            Location::create([
                'name' => 'Liip AG',
                'complement' => 'Liip Arena',
                'address' => 'Limmatstrasse 183',
                'postal_code' => '8005',
                'city' => 'Zurich',
                'canton' => 'ZH',
                'country_id' => 1,
                'is_published' => 1,
            ]);

            Location::create([
                'name' => 'cyon GmbH',
                'address' => 'Brunngässlein 12',
                'postal_code' => '4052',
                'city' => 'Basel',
                'canton' => 'BS',
                'country_id' => 1,
                'is_published' => 1,
            ]);

        }
    }
}
