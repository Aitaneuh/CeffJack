<?php
namespace App\Http\Controllers;

use App\Models\BlackjackGame;
use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Récupérer les parties du joueur
        $games = BlackjackGame::where('user_id', $user->id)->get();

        $gamesPlayed       = $games->count();
        $wins              = $games->where('result', 'won')->count();
        $busts             = $games->where('player_bust', true)->count();
        $blackjacks        = $games->where('player_blackjack', true)->count();
        $averageStandScore = $gamesPlayed > 0
        ? round($games->where('player_bust', false)->avg('player_total'), 2)
        : null;
        $averageBet = $gamesPlayed > 0 ? round($games->avg('bet'), 2) : null;
        $totalWon   = $games->sum('payout');
        $totalBet   = $games->sum('bet');
        $maxWin     = $games->max('profit');
        $maxLoss    = $games->min('profit');

        // Historique agrégé par jour
        $history = BlackjackGame::where('user_id', $user->id)
            ->selectRaw('
                            DATE(created_at) as date,
                            COUNT(*) as total,
                            SUM(CASE WHEN result = "won" THEN 1 ELSE 0 END) as wins,
                            SUM(player_bust) as busts,
                            SUM(player_blackjack) as blackjacks,
                            SUM(payout) as total_won,
                            SUM(bet) as total_bet,
                            SUM(payout - bet) as profit
                        ')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get();

        // Regroupement des stats
        $stats = [
            'games_played'        => $gamesPlayed,
            'wins'                => $wins,
            'busts'               => $busts,
            'blackjacks'          => $blackjacks,
            'average_stand_score' => $averageStandScore,
            'average_bet'         => $averageBet,
            'total_won'           => $totalWon,
            'total_bet'           => $totalBet,
            'max_win'             => $maxWin,
            'max_loss'            => $maxLoss,
            'account_created_at'  => $user->created_at,
        ];

        return view('stats', [
            'stats'   => $stats,
            'history' => $history,
        ]);
    }
}
