<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return Category::create($data);
    }

    public function show(string $id)
    {
        return Category::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,'.$category->id,
        ]);
        if (isset($data['name']) && empty($request->input('slug'))) {
            $data['slug'] = Str::slug($data['name']);
        }
        $category->update($data);

        return $category->fresh();
    }

    public function destroy(string $id)
    {
        Category::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
