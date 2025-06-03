<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (! auth()->check() || ! auth()->user()->is_admin) {
            abort(403, 'Forbidden - You do not have access to this page.');
        }

        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('admin', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot revoke your own admin rights.');
        }

        $user->is_admin = ! $user->is_admin;
        $user->save();

        return back()->with('success', 'User admin status updated.');
    }

    public function resetBalance(User $user)
    {
        $user->balance = 0;
        $user->save();

        return back()->with('success', 'User balance reset.');
    }

    public function updateBalance(Request $request, User $user)
    {
        $request->validate([
            'balance' => 'required|numeric|min:0',
        ]);

        $user->balance = $request->input('balance');
        $user->save();

        return back()->with('success', 'User balance updated.');
    }
}
