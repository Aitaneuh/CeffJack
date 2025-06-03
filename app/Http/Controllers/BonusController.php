<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class BonusController extends Controller
{
    public function claim(Request $request)
    {
        $user = Auth::user();
        $now  = Carbon::now();

        // Assure-toi que last_claimed_bonus_at est un objet Carbon
        $lastClaim = $user->last_claimed_bonus_at;

        // Vérifie s'il doit attendre encore
        if ($lastClaim && $lastClaim->diffInHours($now) < 1) {
            return redirect()->back()->with('error', 'You must wait before claiming again.');
        }

        $bonusAmount = 5000;

        $user->balance += $bonusAmount;
        $user->last_claimed_bonus_at = $now;
        $user->save();

        return redirect()->back()->with('success', "You received $$bonusAmount!");
    }

    public function show()
    {
        $user      = Auth::user();
        $lastClaim = $user->last_claimed_bonus_at;

        // Prochain bonus possible 1h après le dernier
        $nextAvailable    = $lastClaim ? $lastClaim->copy()->addHour() : now();
        $remainingSeconds = max(0, $nextAvailable->diffInSeconds(now()));

        return view('dashboard', compact('remainingSeconds'));
    }
}
