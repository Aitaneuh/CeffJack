<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlackjackGame;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminStatsController extends Controller
{
    public function index(): View
    {
        // Vérifie si l'utilisateur est connecté et s'il est admin
        if (! auth()->check() || ! auth()->user()->is_admin) {
            // Interdit l'accès si non admin
            abort(403, 'Forbidden - You do not have access to this page.');
        }

        // Nombre total d'utilisateurs dans le système
        $totalUsers = User::count();

        // Nombre total de parties de Blackjack jouées
        $totalGames = BlackjackGame::count();

        // Somme totale des mises posées (colonne `bet`)
        $totalBets = BlackjackGame::sum('bet');

        // Somme totale des paiements versés aux joueurs (mise + profit)
        $totalPayouts = BlackjackGame::sum('payout');

        // Somme totale des profits réalisés (peut être positif ou négatif)
        $totalProfit = BlackjackGame::sum('profit');

        // Mise moyenne par partie (évite la division par zéro)
        $averageBet = $totalGames ? $totalBets / $totalGames : 0;

        // Plus gros gain unique (le profit le plus élevé strictement positif)
        $biggestWin = BlackjackGame::where('profit', '>', 0)->max('profit') ?? 0;

        // Plus grosse perte unique (profit le plus négatif, converti en positif pour l'affichage)
        $biggestLoss = abs(
            BlackjackGame::where('profit', '<', 0)->min('profit') ?? 0
        );

        // Estimation des utilisateurs "en ligne" : actifs dans les 60 dernières minutes
        $timeThreshold = now()->subMinutes(60);
        $usersOnline   = User::where('last_claimed_bonus_at', '>=', $timeThreshold)->count();

        // Comptage des types de résultats (won, lost, draw, etc.)
        $resultsCount = BlackjackGame::select('result', DB::raw('count(*) as total'))
            ->groupBy('result')
            ->pluck('total', 'result')
            ->toArray();

        // Nombre total de busts (joueur qui dépasse 21)
        $totalBusts = BlackjackGame::where('player_bust', true)->count();

        // Nombre total de blackjacks réalisés
        $totalBlackjacks = BlackjackGame::where('player_blackjack', true)->count();

        // Somme totale de tous les soldes utilisateurs (balance actuelle)
        $totalBalance = User::sum('balance');

        // Top 5 des joueurs les plus riches (par balance décroissante)
        $topUsers = User::orderBy('balance', 'desc')
            ->limit(5)
            ->get(['id', 'name', 'balance']);

        // Regroupe toutes les stats dans un tableau pour l'envoyer à la vue
        $stats = [
            'total_users'      => $totalUsers,
            'total_games'      => $totalGames,
            'total_bets'       => $totalBets,
            'total_payouts'    => $totalPayouts,
            'total_profit'     => $totalProfit,
            'average_bet'      => $averageBet,
            'biggest_win'      => $biggestWin,
            'biggest_loss'     => $biggestLoss,
            'users_online'     => $usersOnline,
            'results_count'    => $resultsCount,
            'total_busts'      => $totalBusts,
            'total_blackjacks' => $totalBlackjacks,
            'total_balance'    => $totalBalance,
            'top_users'        => $topUsers,
        ];

        // Récupère l’historique des parties par jour avec agrégation :
        // - nombre total
        // - nombre de victoires
        // - nombre de busts
        // - nombre de blackjacks
        $history = DB::table('blackjack_games')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total'),
                DB::raw('sum(case when result = "won" then 1 else 0 end) as wins'),
                DB::raw('sum(case when player_bust = 1 then 1 else 0 end) as busts'),
                DB::raw('sum(case when player_blackjack = 1 then 1 else 0 end) as blackjacks'),
                DB::raw('sum(bet) as total_bets'),
                DB::raw('sum(payout) as total_payouts')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Ajoute l'historique dans les stats
        $stats['history'] = $history;

        // Envoie les données à la vue adminstats.blade.php
        return view('adminstats', compact('stats'));
    }
}
