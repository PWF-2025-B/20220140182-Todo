<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index()
    {
        $search = request('search');

        $query = User::where('id', '!=', 1);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')
                       ->paginate(20)
                       ->withQueryString();

        return view('user.index', compact('users'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id == 1) {
            return redirect()->route('user.index')
                             ->with('danger', 'Delete user failed!');
        }

        // Hapus semua todo milik user ini
        $user->todo()->delete();

        // Hapus user
        $user->delete();

        return back()->with('success', 'Delete user successfully!');
    }

    public function makeadmin(User $user): RedirectResponse
    {
        $user->timestamps = false;
        $user->is_admin = true;
        $user->save();

        return back()->with('success', 'Make Admin Successfully!');
    }

    public function removeadmin(User $user): RedirectResponse
    {
        if ($user->id == 1) {
            return redirect()->route('user.index');
        }

        $user->timestamps = false;
        $user->is_admin = false;
        $user->save();

        return back()->with('success', 'Remove Admin Successfully!');
    }
}