<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@cakes.com')->first();
        if (! $customer) {
            return;
        }

        $products = Product::where('is_available', true)->limit(5)->get();
        if ($products->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($customer, $products) {
            foreach ($products as $idx => $product) {
                $rating = [5, 4, 5, 4, 3][$idx % 5];
                $comment = [
                    'Absolutely delicious and well decorated!',
                    'Great flavor and smooth frosting.',
                    'Super fresh and the custom details matched perfectly.',
                    'Really nice presentation. Will order again.',
                    'Good cake overall, would love a bit more sweetness next time.',
                ][$idx % 5];

                Review::updateOrCreate(
                    ['user_id' => $customer->id, 'product_id' => $product->id],
                    [
                        'rating' => $rating,
                        'comment' => $comment,
                    ]
                );
            }
        });
    }
}
