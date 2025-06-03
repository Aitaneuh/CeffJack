<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboard = User::query()
            ->select([
                'users.id',
                'users.name',
                'users.balance', // ✅ balance ajoutée ici
                DB::raw('COUNT(blackjack_games.id) AS games_played'),
                DB::raw('SUM(CASE WHEN blackjack_games.result = "won" THEN 1 ELSE 0 END) AS wins'),
                DB::raw('COALESCE(SUM(blackjack_games.bet), 0) AS total_bet'),
                DB::raw('COALESCE(SUM(blackjack_games.payout), 0) AS total_payout'),
                DB::raw('COALESCE(SUM(blackjack_games.profit), 0) AS total_profit'),
            ])
            ->leftJoin('blackjack_games', 'users.id', '=', 'blackjack_games.user_id')
            ->where('users.is_admin', false)
            ->groupBy('users.id', 'users.name', 'users.balance') // ✅ balance ajoutée ici aussi
            ->orderByDesc('users.balance')                       // tu peux remplacer par ->orderByDesc('balance') si tu veux trier là-dessus
            ->orderByDesc('wins')
            ->paginate(15);

        return view('leaderboard', compact('leaderboard'));
    }
}
