<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::query()->with('roles');

        if ($request->filled('search')) {
            $s = '%'.$request->search.'%';
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', $s)->orWhere('email', 'like', $s);
            });
        }

        return $q->orderBy('name')->get();
    }

    public function show(string $id)
    {
        return User::with('roles')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'role' => ['required', Rule::in(['admin', 'baker', 'customer_service', 'customer'])],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);
        $user->assignRole($data['role']);

        return $user->load('roles');
    }

    public function update(Request $r, $id)
    {
        $user = User::findOrFail($id);
        $payload = $r->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:6',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);
        if (! empty($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        }
        $user->update($payload);

        return $user->fresh()->load('roles');
    }

    public function assignRole(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'role' => ['required', Rule::in(['admin', 'baker', 'customer_service', 'customer'])],
        ]);
        $user->syncRoles([$data['role']]);

        return $user->load('roles');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
