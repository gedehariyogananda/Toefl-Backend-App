<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Campain;
use App\Models\CustomerLoundry;
use App\Models\HistoryCampain;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ReqCamp;
use App\Models\SpesificationLoundry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name_user' => "Maya",
            'id_loundry' => "#" . Str::uuid(),
            'image_loundry_user' => '-',
            'email_user' => "maya@gmail.com",
            'phone_number_user' => "08123456789",
            'password' => Hash::make('password'),
            'loundry_name_user' => "Maya Loundry",
        ]);

        SpesificationLoundry::create([
            'user_id' => 1,
            'name_spesification_loundry' => "Cuci Kering",
            'price_kg_loundry' => 5000,
        ]);

        SpesificationLoundry::create([
            'user_id' => 1,
            'name_spesification_loundry' => "Cuci Basah",
            'price_kg_loundry' => 4000,
        ]);

        SpesificationLoundry::create([
            'user_id' => 1,
            'name_spesification_loundry' => "Cuci Setrika",
            'price_kg_loundry' => 7000,
        ]);

        PaymentMethod::create([
            'user_id' => 1,
            'name_payment_method' => "OVO",
        ]);

        PaymentMethod::create([
            'user_id' => 1,
            'name_payment_method' => "DANA",
        ]);

        PaymentMethod::create([
            'user_id' => 1,
            'name_payment_method' => "GOPAY",
        ]);

        CustomerLoundry::create([
            'user_id' => 1,
            'name_customer_loundry' => "Ary",
            'id_customer' => "#123123122",
            'spesification_loundry_id' => "1",
            'quantity_loundry' => "2",
            'result_price_loundry' => "10000",
            'start_loundry_customer' => now(),
            'end_loundry_customer' => now(),
            'phone_number_customer_loundry' => "08123456789",
            'address_customer_loundry' => "Jl. Raya",
            'payment_method_id' => 1,
        ]);

        CustomerLoundry::create([
            'user_id' => 1,
            'id_customer' => "#123123131",
            'name_customer_loundry' => "Oeyyy",
            'spesification_loundry_id' => "1",
            'quantity_loundry' => "2",
            'result_price_loundry' => "20000",
            'start_loundry_customer' => now(),
            'end_loundry_customer' => now(),
            'phone_number_customer_loundry' => "08123456789",
            'address_customer_loundry' => "Jl. Raya",
            'payment_method_id' => 1,
        ]);
    }
}
