<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = is_string($request->query('q')) ? trim($request->query('q')) : null;

        $users = User::withCount(['products', 'orders'])
            ->when($q, fn ($qb) => $qb->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")))
            ->orderByRaw("CASE role WHEN 'admin' THEN 0 WHEN 'editor' THEN 1 ELSE 2 END")
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users', compact('users', 'q'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate(['role' => ['required', Rule::in(User::ROLES)]]);

        if ($user->is($request->user())) {
            return back()->with('error', 'Kamu tidak bisa mengubah role akunmu sendiri.');
        }

        $user->role = $validated['role']; // diisi eksplisit: role tidak mass-assignable
        $user->save();

        return back()->with('success', "Role {$user->name} diubah menjadi {$user->role_label}.");
    }
}
