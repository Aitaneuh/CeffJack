<?php
namespace App\Http\Controllers;

use App\Models\BlackjackGame;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistiques classiques
        $gamesPlayed = BlackjackGame::where('user_id', $user->id)->count();
        $wins        = BlackjackGame::where('user_id', $user->id)->where('result', 'won')->count();
        $winRate     = $gamesPlayed > 0 ? round($wins / $gamesPlayed * 100, 2) : 0;

        $lastGame = BlackjackGame::where('user_id', $user->id)->latest()->first();

        $nextClaimTime = $user->last_claimed_bonus_at
        ? Carbon::parse($user->last_claimed_bonus_at)->addHour()
        : now();

        $remainingSeconds = $nextClaimTime->isFuture()
        ? now()->diffInSeconds($nextClaimTime)
        : 0;

        return view('dashboard', [
            'stats'         => [
                'games_played' => $gamesPlayed,
                'wins'         => $wins,
                'win_rate'     => $winRate,
            ],
            'lastGame'      => $lastGame,
            'bonusCooldown' => $remainingSeconds,
            'nextClaimTime' => $nextClaimTime,
        ]);
    }
}
