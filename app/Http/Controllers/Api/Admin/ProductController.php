<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $r)
    {
        return Product::create($r->all());
    }

    public function update(Request $r, $id)
    {
        $p = Product::findOrFail($id);
        $p->update($r->all());
        return $p;
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
