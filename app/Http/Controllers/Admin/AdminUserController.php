<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        $users = User::withCount('products')
            ->when($search, fn ($q) => $q->where(fn ($q) =>
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
            ))
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.users', compact('users', 'search'));
    }

    public function ban(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot ban an admin.');
        }

        $user->update(['is_banned' => !$user->is_banned]);

        return back()->with('success', $user->is_banned ? "{$user->name} banned." : "{$user->name} unbanned.");
    }

    public function makeAdmin(User $user)
    {
        $user->update(['role' => 'admin']);

        return back()->with('success', "{$user->name} is now an admin.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}
