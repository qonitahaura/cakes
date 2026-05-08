<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customization;
use App\Models\CustomizationOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomizationController extends Controller
{
    public function index()
    {
        return Customization::with('options')->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,multi_select,text',
            'options' => 'nullable|array',
            'options.*.option_name' => 'required_with:options|string|max:255',
            'options.*.additional_price' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($data) {
            $options = $data['options'] ?? [];
            unset($data['options']);
            $c = Customization::create($data);
            foreach ($options as $opt) {
                CustomizationOption::create([
                    'customization_id' => $c->id,
                    'option_name' => $opt['option_name'],
                    'additional_price' => $opt['additional_price'] ?? 0,
                ]);
            }

            return $c->load('options');
        });
    }

    public function show(string $id)
    {
        return Customization::with('options')->findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $customization = Customization::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:select,multi_select,text',
            'options' => 'nullable|array',
            'options.*.option_name' => 'required_with:options|string|max:255',
            'options.*.additional_price' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $customization, $data) {
            $options = $data['options'] ?? null;
            unset($data['options']);
            $customization->update($data);

            if (is_array($options)) {
                $customization->options()->delete();
                foreach ($options as $opt) {
                    CustomizationOption::create([
                        'customization_id' => $customization->id,
                        'option_name' => $opt['option_name'],
                        'additional_price' => $opt['additional_price'] ?? 0,
                    ]);
                }
            }

            return $customization->fresh()->load('options');
        });
    }

    public function destroy(string $id)
    {
        Customization::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function destroyOption(string $customizationId, string $optionId)
    {
        $opt = CustomizationOption::where('customization_id', $customizationId)
            ->whereKey($optionId)
            ->firstOrFail();
        $opt->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
