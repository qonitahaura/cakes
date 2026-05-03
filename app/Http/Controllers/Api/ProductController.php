<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('customizations.options')->get();
    }

    public function show($id)
    {
        return Product::with('customizations.options')->findOrFail($id);
    }
}
