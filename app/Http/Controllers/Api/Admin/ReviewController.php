<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        return Review::with(['user:id,name,email', 'product:id,name'])->latest()->get();
    }

    public function destroy(string $id)
    {
        Review::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
