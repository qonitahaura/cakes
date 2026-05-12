<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $q = Review::query()
            ->with(['user:id,name,email', 'product:id,name'])
            ->latest();

        // Search: customer name
        if ($request->filled('search')) {
            $s = $request->string('search')->trim();
            $q->whereHas('user', function ($qq) use ($s) {
                $qq->where('name', 'like', '%' . $s . '%');
            });
        }

        // Filter: rating
        if ($request->filled('rating')) {
            $rating = (int) $request->input('rating');
            $q->where('rating', $rating);
        }

        // Filter: product
        if ($request->filled('product_id')) {
            $q->where('product_id', (int) $request->input('product_id'));
        }

        // Sort newest/oldest
        $sortUi = $request->string('sort')->value(); // newest|oldest
        $dir = $sortUi === 'oldest' ? 'asc' : 'desc';
        $q->reorder()->orderBy('created_at', $dir);

        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min(100, $perPage));
        $page = max(1, (int) $request->input('page', 1));

        $paginator = $q->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }


    public function destroy(string $id)
    {
        Review::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
