<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

                $usersQuery = User::withCount([
            'todos as todos_done_count' => fn($query) => $query->where('is_done', true),
            'todos as todos_undone_count' => fn($query) => $query->where('is_done', false)
        ])
        ->where('id', '!=', 1);

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->paginate(10)->withQueryString();

        return view('user.index', compact('users'));
    }

    // Contoh route untuk make admin / remove admin (asumsi ada kolom is_admin)
    public function makeAdmin(User $user)
    {
        $user->update(['is_admin' => true]);
        return redirect()->route('user.index')->with('success', 'User is now Admin');
    }

    public function removeAdmin(User $user)
    {
        $user->update(['is_admin' => false]);
        return redirect()->route('user.index')->with('success', 'Admin rights removed');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('danger', 'User deleted');
    }
}
