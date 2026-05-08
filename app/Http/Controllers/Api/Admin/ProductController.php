<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(
        protected UploadService $uploadService
    ) {}

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|string|max:2048',
            'image' => 'nullable|image|max:5120',
            'is_available' => 'sometimes|boolean',
            'is_custom' => 'sometimes|boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        if ($r->hasFile('image')) {
            $data['image_url'] = $this->uploadService->uploadProductImage($r->file('image'));
        }
        unset($data['image']);

        return Product::create($data);
    }

    public function update(Request $r, $id)
    {
        $p = Product::findOrFail($id);
        $data = $r->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,'.$p->id,
            'description' => 'nullable|string',
            'base_price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'image_url' => 'nullable|string|max:2048',
            'image' => 'nullable|image|max:5120',
            'is_available' => 'sometimes|boolean',
            'is_custom' => 'sometimes|boolean',
        ]);

        if ($r->hasFile('image')) {
            $data['image_url'] = $this->uploadService->uploadProductImage($r->file('image'));
        }
        unset($data['image']);

        if (isset($data['name']) && empty($r->input('slug'))) {
            $data['slug'] = Str::slug($data['name']);
        }

        $p->update($data);

        return $p->fresh()->load('customizations.options');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function attachCustomizations(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'customizations' => 'required|array',
            'customizations.*.id' => 'required|exists:customizations,id',
            'customizations.*.is_required' => 'nullable|boolean',
            'customizations.*.max_select' => 'nullable|integer|min:1',
            'customizations.*.sort_order' => 'nullable|integer|min:0',
        ]);

        $sync = [];
        foreach ($data['customizations'] as $row) {
            $sync[$row['id']] = [
                'is_required' => $row['is_required'] ?? false,
                'max_select' => $row['max_select'] ?? null,
                'sort_order' => $row['sort_order'] ?? 0,
            ];
        }
        $product->customizations()->sync($sync);

        return $product->fresh()->load('customizations.options');
    }
}
