<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Customization;
use App\Models\CustomizationOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure a default category set exists (dashboard expects categories).
        $categories = [
            'Roti',
            'Kue Tradisional',
            'Chiffon & Roll',
            'Donat',
            'Pastry & Danish',
            'Puding',
            'Kue',
            'Lapis',
            'Kue Kering',
            'Snack Box',
        ];

        $categoryMap = [];

        foreach ($categories as $cat) {
            $categoryMap[$cat] = Category::firstOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat]
            );
        }

        // Create base customizations + options.
        $flavor = $this->createCustomization('Flavor', 'select');
        $flavorOptions = [
            'Vanilla',
            'Chocolate',
            'Red Velvet',
        ];
        foreach ($flavorOptions as $opt) {
            $this->createOption($flavor->id, $opt, 0);
        }

        $size = $this->createCustomization('Size', 'select');
        $sizeOptions = [
            ['6 inch', 0],
            ['8 inch', 15000],
            ['10 inch', 30000],
        ];
        foreach ($sizeOptions as [$name, $price]) {
            $this->createOption($size->id, $name, $price);
        }

        $toppings = $this->createCustomization('Toppings', 'multi_select');
        $toppingOptions = [
            ['Berries', 7000],
            ['Choco Chips', 6000],
            ['Macarons', 12000],
        ];
        foreach ($toppingOptions as [$name, $price]) {
            $this->createOption($toppings->id, $name, $price);
        }

        $decoration = $this->createCustomization('Decoration', 'select');
        $decorationOptions = [
            ['Classic Cream', 5000],
            ['Fondant Wrap', 18000],
            ['Butterfly Theme', 22000],
        ];
        foreach ($decorationOptions as [$name, $price]) {
            $this->createOption($decoration->id, $name, $price);
        }

        $customText = $this->createCustomization('Custom Text', 'text');
        $this->createOption($customText->id, 'Custom message', 3000);

        $additionalPriceDeco = $this->createCustomization('Extra Decoration', 'select');
        $extraDecoOptions = [
            ['Gold Dust', 12000],
            ['Edible Flowers', 14000],
        ];
        foreach ($extraDecoOptions as [$name, $price]) {
            $this->createOption($additionalPriceDeco->id, $name, $price);
        }

        // 5 customizable products
        $customizable = [
            [
                'name' => 'Vanilla Signature',
                'base_price' => 120000,
                'category' => 'Kue',
            ],
            [
                'name' => 'Choco Burst',
                'base_price' => 135000,
                'category' => 'Chiffon & Roll',
            ],
            [
                'name' => 'Red Velvet Rose',
                'base_price' => 150000,
                'category' => 'Pastry & Danish',
            ],
            [
                'name' => 'Velvet Party Slice',
                'base_price' => 98000,
                'category' => 'Lapis',
            ],
            [
                'name' => 'Midnight Choco Bloom',
                'base_price' => 65000,
                'category' => 'Kue Tradisional',
            ],
        ];

        // 5 non-customizable products
        $fixed = [
            [
                'name' => 'Classic Cheesecake',
                'base_price' => 45000,
                'category' => 'Donat',
            ],
            [
                'name' => 'Lemon Drizzle',
                'base_price' => 35000,
                'category' => 'Roti',
            ],
            [
                'name' => 'Strawberry Cloud',
                'base_price' => 40000,
                'category' => 'Puding',
            ],
            [
                'name' => 'Banoffee Dream',
                'base_price' => 50000,
                'category' => 'Snack Box',
            ],
            [
                'name' => 'Cookie Crunch Cake',
                'base_price' => 55000,
                'category' => 'Kue Kering',
            ],
        ];

        foreach ($customizable as $p) {
            $product = Product::firstOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'name' => $p['name'],
                    'description' => 'Demo customizable cake. Build your perfect cake with flavors, sizes, toppings, and decorations.',
                    'category_id' => $categoryMap[$p['category']]->id,
                    'base_price' => $p['base_price'],
                    'image_url' => 'https://picsum.photos/seed/' . Str::slug($p['name']) . '/600/400',
                    'is_available' => true,
                    'is_custom' => true,
                ]
            );

            // Attach customizations with pivot settings.
            $this->attachCustomization($product->id, $flavor->id, true, 1, 1);
            $this->attachCustomization($product->id, $size->id, true, 1, 2);
            $this->attachCustomization($product->id, $toppings->id, false, 3, 3);
            $this->attachCustomization($product->id, $decoration->id, false, 1, 4);
            $this->attachCustomization($product->id, $customText->id, false, 1, 5);
            $this->attachCustomization($product->id, $additionalPriceDeco->id, false, 1, 6);
        }

        foreach ($fixed as $p) {
            Product::firstOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'name' => $p['name'],
                    'description' => 'Demo non-customizable cake with a fixed price.',
                    'category_id' => $categoryMap[$p['category']]->id,
                    'base_price' => $p['base_price'],
                    'image_url' => 'https://picsum.photos/seed/' . Str::slug($p['name']) . '/600/400',
                    'is_available' => true,
                    'is_custom' => false,
                ]
            );
        }
    }

    private function createCustomization(string $name, string $type): Customization
    {
        return Customization::firstOrCreate(
            ['name' => $name],
            ['type' => $type]
        );
    }

    private function createOption(int $customizationId, string $optionName, float $additionalPrice): void
    {
        CustomizationOption::firstOrCreate(
            [
                'customization_id' => $customizationId,
                'option_name' => $optionName,
            ],
            [
                'additional_price' => $additionalPrice,
            ]
        );
    }

    private function attachCustomization(int $productId, int $customizationId, bool $isRequired, int $maxSelect, int $sortOrder): void
    {
        // product_customizations pivot (see Product->customizations relation)
        $product = Product::query()->find($productId);
        if (! $product) {
            return;
        }

        if (! $product->customizations()->where('customizations.id', $customizationId)->exists()) {
            $product->customizations()->attach($customizationId, [
                'is_required' => $isRequired,
                'max_select' => $maxSelect,
                'sort_order' => $sortOrder,
            ]);
        } else {
            // Update pivot fields if already exists.
            $product->customizations()->updateExistingPivot($customizationId, [
                'is_required' => $isRequired,
                'max_select' => $maxSelect,
                'sort_order' => $sortOrder,
            ]);
        }
    }
}
