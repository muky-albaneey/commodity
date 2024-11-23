<?php

namespace Database\Seeders;

use App\Models\Commodity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommoditySeeder extends Seeder
{
    public function run()
    {
        $commodities = [
            // GRAINS
            [
                'name' => 'Maize',
                'symbol' => 'SMAZ',
                'description' => 'Nigerian Yellow Maize - Grade 1, with maximum 13% moisture content. Widely used for animal feed and food processing.',
                'image' => 'https://assets.commodities-db.com/maize-yellow-grade1.jpg',
                'minimum_quantity' => 10,
                'maximum_quantity' => 1000,
                'category' => 'Grains',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 13%',
                    'foreign_matter' => 'Maximum 2%',
                    'broken_grains' => 'Maximum 5%',
                    'storage_life' => '12 months under proper conditions',
                    'packaging' => '50kg PP bags or bulk loading'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '10 MT',
            ],
            [
                'name' => 'Paddy Rice',
                'symbol' => 'SPRL',
                'description' => 'Nigerian Long Grain Paddy Rice, with maximum 14% moisture content. Premium quality for rice mills.',
                'image' => 'https://assets.commodities-db.com/paddy-rice-premium.jpg',
                'minimum_quantity' => 20,
                'maximum_quantity' => 2000,
                'category' => 'Grains',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 14%',
                    'foreign_matter' => 'Maximum 1.5%',
                    'broken_grains' => 'Maximum 4%',
                    'storage_life' => '12 months under proper conditions',
                    'packaging' => '75kg PP bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '20 MT',
            ],
            [
                'name' => 'Sorghum',
                'symbol' => 'SSGM',
                'description' => 'Red Sorghum - Grade 1, suitable for food and beverage production. High tannin content.',
                'image' => 'https://assets.commodities-db.com/sorghum-red.jpg',
                'minimum_quantity' => 10,
                'maximum_quantity' => 1000,
                'category' => 'Grains',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 12%',
                    'foreign_matter' => 'Maximum 2%',
                    'tannin_content' => 'High',
                    'packaging' => '50kg PP bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '10 MT',
            ],
            [
                'name' => 'Millet',
                'symbol' => 'SMLT',
                'description' => 'Pearl Millet - Premium Grade, ideal for flour production and animal feed.',
                'image' => 'https://assets.commodities-db.com/pearl-millet.jpg',
                'minimum_quantity' => 5,
                'maximum_quantity' => 500,
                'category' => 'Grains',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 12%',
                    'purity' => 'Minimum 98%',
                    'packaging' => '50kg PP bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '5 MT',
            ],

            // OILSEEDS
            [
                'name' => 'Soybean',
                'symbol' => 'SSBS',
                'description' => 'Nigerian Soybeans - Grade 1, with minimum 40% protein content. Ideal for oil extraction and animal feed.',
                'image' => 'https://assets.commodities-db.com/soybean-grade1.jpg',
                'minimum_quantity' => 5,
                'maximum_quantity' => 500,
                'category' => 'Oilseeds',
                'specifications' => json_encode([
                    'protein_content' => 'Minimum 40%',
                    'moisture' => 'Maximum 12%',
                    'foreign_matter' => 'Maximum 1%',
                    'packaging' => '50kg PP bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '5 MT',
            ],
            [
                'name' => 'Groundnut',
                'symbol' => 'SGNT',
                'description' => 'Shelled Groundnuts - Premium Grade, suitable for oil extraction and direct consumption.',
                'image' => 'https://assets.commodities-db.com/groundnuts-shelled.jpg',
                'minimum_quantity' => 5,
                'maximum_quantity' => 500,
                'category' => 'Oilseeds',
                'specifications' => json_encode([
                    'oil_content' => 'Minimum 45%',
                    'moisture' => 'Maximum 8%',
                    'aflatoxin' => 'Below 4ppb',
                    'packaging' => '50kg jute bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '5 MT',
            ],

            // CASH CROPS
            [
                'name' => 'Cocoa',
                'symbol' => 'SCOA',
                'description' => 'Nigerian Cocoa Beans - Grade A, fermented and dried. Premium quality for chocolate production.',
                'image' => 'https://assets.commodities-db.com/cocoa-beans-premium.jpg',
                'minimum_quantity' => 1,
                'maximum_quantity' => 100,
                'category' => 'Cash Crops',
                'specifications' => json_encode([
                    'bean_count' => '< 100 beans per 100g',
                    'moisture' => 'Maximum 7.5%',
                    'fermentation' => 'Well fermented',
                    'packaging' => '65kg jute bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+3',
                'contract_size' => '1 MT',
            ],
            [
                'name' => 'Cotton',
                'symbol' => 'SCTN',
                'description' => 'Nigerian Raw Cotton - Premium Grade, machine picked, suitable for textile production.',
                'image' => 'https://assets.commodities-db.com/cotton-raw.jpg',
                'minimum_quantity' => 5,
                'maximum_quantity' => 500,
                'category' => 'Cash Crops',
                'specifications' => json_encode([
                    'staple_length' => '28-32mm',
                    'moisture' => 'Maximum 8.5%',
                    'micronaire' => '3.5-4.9',
                    'packaging' => 'Compressed bales'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '5 MT',
            ],

            // PULSES
            [
                'name' => 'Cowpea',
                'symbol' => 'SCWP',
                'description' => 'Brown Cowpea (Beans) - Premium Grade, high protein content. Essential for food security.',
                'image' => 'https://assets.commodities-db.com/cowpea-brown.jpg',
                'minimum_quantity' => 5,
                'maximum_quantity' => 500,
                'category' => 'Pulses',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 12%',
                    'foreign_matter' => 'Maximum 0.5%',
                    'damaged_grains' => 'Maximum 1%',
                    'packaging' => '50kg PP bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '5 MT',
            ],
            [
                'name' => 'Bambara Nuts',
                'symbol' => 'SBMN',
                'description' => 'Bambara Groundnuts - Grade 1, high nutritional value. Popular in West African markets.',
                'image' => 'https://assets.commodities-db.com/bambara-nuts.jpg',
                'minimum_quantity' => 2,
                'maximum_quantity' => 200,
                'category' => 'Pulses',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 10%',
                    'purity' => 'Minimum 98%',
                    'packaging' => '50kg jute bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '2 MT',
            ],

            // TREE NUTS
            [
                'name' => 'Cashew Nuts',
                'symbol' => 'SCNR',
                'description' => 'Raw Cashew Nuts - Premium Grade, whole kernels. Export quality.',
                'image' => 'https://assets.commodities-db.com/cashew-raw.jpg',
                'minimum_quantity' => 2,
                'maximum_quantity' => 200,
                'category' => 'Tree Nuts',
                'specifications' => json_encode([
                    'nut_count' => '180-190 per kg',
                    'moisture' => 'Maximum 10%',
                    'kernel_outturn' => 'Minimum 47 lbs',
                    'packaging' => '80kg jute bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '2 MT',
            ],
            [
                'name' => 'Palm Kernel',
                'symbol' => 'SPKN',
                'description' => 'Palm Kernels - Grade A, suitable for oil extraction. High quality Nigerian origin.',
                'image' => 'https://assets.commodities-db.com/palm-kernel.jpg',
                'minimum_quantity' => 10,
                'maximum_quantity' => 1000,
                'category' => 'Tree Nuts',
                'specifications' => json_encode([
                    'oil_content' => 'Minimum 48%',
                    'moisture' => 'Maximum 8%',
                    'free_fatty_acid' => 'Maximum 5%',
                    'packaging' => '50kg PP bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '10 MT',
            ],

            // SPICES
            [
                'name' => 'Ginger',
                'symbol' => 'SGNG',
                'description' => 'Split Dried Ginger - Premium Grade, high oleoresin content. Export quality.',
                'image' => 'https://assets.commodities-db.com/ginger-dried.jpg',
                'minimum_quantity' => 1,
                'maximum_quantity' => 100,
                'category' => 'Spices',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 12%',
                    'oleoresin' => 'Minimum 4%',
                    'fiber' => 'Maximum 8%',
                    'packaging' => '25kg PP bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '1 MT',
            ],
            [
                'name' => 'Sesame Seeds',
                'symbol' => 'SSSD',
                'description' => 'Natural Sesame Seeds - Premium Grade, 99.95% purity. Ideal for oil extraction and export.',
                'image' => 'https://assets.commodities-db.com/sesame-natural.jpg',
                'minimum_quantity' => 5,
                'maximum_quantity' => 500,
                'category' => 'Spices',
                'specifications' => json_encode([
                    'purity' => 'Minimum 99.95%',
                    'moisture' => 'Maximum 6%',
                    'oil_content' => 'Minimum 48%',
                    'packaging' => '50kg PP bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '5 MT',
            ],

            // ROOTS & TUBERS
            [
                'name' => 'Dried Cassava',
                'symbol' => 'SCAS',
                'description' => 'Dried Cassava Chips - Industrial Grade, suitable for ethanol and starch production.',
                'image' => 'https://assets.commodities-db.com/cassava-dried.jpg',
                'minimum_quantity' => 20,
                'maximum_quantity' => 2000,
                'category' => 'Roots & Tubers',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 13%',
                    'starch_content' => 'Minimum 65%',
                    'chip_size' => '2-5cm',
                    'packaging' => '50kg PP bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '20 MT',
            ],
            [
                'name' => 'Yam Flour',
                'symbol' => 'SYMF',
                'description' => 'Premium Yam Flour - Food Grade, fine texture. Ideal for domestic and export markets.',
                'image' => 'https://assets.commodities-db.com/yam-flour.jpg',
                'minimum_quantity' => 5,
                'maximum_quantity' => 500,
                'category' => 'Roots & Tubers',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 12%',
                    'fineness' => '100 mesh',
                    'color' => 'Creamy white',
                    'packaging' => '25kg PP bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '5 MT',
            ],

            // VEGETABLES
            [
                'name' => 'Dried Okra',
                'symbol' => 'SOKR',
                'description' => 'Dried Sliced Okra - Export Grade, preserved vegetable for international markets.',
                'image' => 'https://assets.commodities-db.com/okra-dried.jpg',
                'minimum_quantity' => 1,
                'maximum_quantity' => 100,
                'category' => 'Vegetables',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 12%',
                    'slice_thickness' => '2-3mm',
                    'color' => 'Natural green',
                    'packaging' => '10kg cartons'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '1 MT',
            ],

            // FRUITS
            [
                'name' => 'Dried Mango',
                'symbol' => 'SDMG',
                'description' => 'Dried Mango Slices - Premium Grade, naturally dried. Export quality.',
                'image' => 'https://assets.commodities-db.com/mango-dried.jpg',
                'minimum_quantity' => 1,
                'maximum_quantity' => 100,
                'category' => 'Fruits',
                'specifications' => json_encode([
                    'moisture' => 'Maximum 15%',
                    'sugar_content' => 'Natural, no added sugar',
                    'size' => 'Uniform slices',
                    'packaging' => '5kg vacuum bags'
                ]),
                'trading_hours' => '9:00 AM - 4:00 PM WAT',
                'settlement_type' => 'T+2',
                'contract_size' => '1 MT',
            ],
        ];

        foreach ($commodities as $commodity) {
            $commodityModel = Commodity::create(array_merge($commodity, [
                'id' => Str::uuid(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]));

            // Initialize market data with zero values
            $commodityModel->market()->create([
                'best_sell' => 0,
                'best_buy' => 0,
                'market_price' => 0,
                'change_24h' => 0,
                'volume_24h' => 0,
                'buyers_count' => 0,
                'sellers_count' => 0,
                'market_value' => 0
            ]);
        }
    }
}
