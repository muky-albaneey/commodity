<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['name' => 'Access Bank', 'code' => '044'],
            ['name' => 'Zenith Bank', 'code' => '057'],
            ['name' => 'First Bank of Nigeria', 'code' => '011'],
            ['name' => 'United Bank for Africa', 'code' => '033'],
            ['name' => 'GT Bank', 'code' => '058'],
            ['name' => 'Union Bank', 'code' => '032'],
            ['name' => 'Ecobank', 'code' => '050'],
            ['name' => 'FCMB', 'code' => '214'],
            ['name' => 'Fidelity Bank', 'code' => '070'],
            ['name' => 'Heritage Bank', 'code' => '030'],
            ['name' => 'Keystone Bank', 'code' => '082'],
            ['name' => 'Polaris Bank', 'code' => '076'],
            ['name' => 'Stanbic IBTC', 'code' => '221'],
            ['name' => 'Sterling Bank', 'code' => '232'],
            ['name' => 'Unity Bank', 'code' => '215'],
            ['name' => 'Wema Bank', 'code' => '035'],
            ['name' => 'Opay', 'code' => '999'],
            ['name' => 'Palmpay', 'code' => '998'],
            ['name' => 'Kuda Bank', 'code' => '997'],
            ['name' => 'VFD Microfinance Bank', 'code' => '566'],
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
} 