<?php
namespace App\Http\Controllers;

use App\Models\BlackjackGame;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Toutes les parties du joueur, ordre chronologique (asc)
        $games = BlackjackGame::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Calcul des stats haut de page pour toutes les parties
        $stats = $this->calculateStatsForGames($games, $user);

        // Historique agrégé par jour (pour afficher le graphe initial)
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

        return view('stats', [
            'stats'   => $stats,
            'history' => $history,
        ]);
    }

    public function getFilteredStats(Request $request)
    {
        $user   = Auth::user();
        $filter = $request->input('filter');

        $query = BlackjackGame::where('user_id', $user->id);

        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case '7days':
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
            case '100games':
                $query->latest()->limit(100);
                break;
            case 'all':
            default:
                // no filter
                break;
        }

        // Récupérer les jeux, en ordre chronologique (asc)
        if ($filter === '100games') {
            $games = $query->get()->sortBy('created_at')->values();
        } else {
            $games = $query->orderBy('created_at')->get();
        }

        // Calcul des stats pour le haut de page selon la sélection
        $stats = $this->calculateStatsForGames($games, $user);

        // Préparer les données pour le graphique selon le filtre

        if (in_array($filter, ['today', '100games'])) {
            // Groupement par bloc de 10 parties (index-based)
            $grouped = $games->chunk(10);

            $data = $grouped->map(function ($group, $index) {
                return [
                    'label'      => 'Games ' . ($index * 10 + 1) . '-' . ($index * 10 + $group->count()),
                    'total'      => $group->count(),
                    'wins'       => $group->where('result', 'won')->count(),
                    'busts'      => $group->where('player_bust', true)->count(),
                    'blackjacks' => $group->where('player_blackjack', true)->count(),
                    'total_won'  => $group->sum('payout'),
                    'total_bet'  => $group->sum('bet'),
                    'profit'     => $group->sum(fn($g) => $g->payout - $g->bet),
                ];
            })->values();
        } else {
            // Groupement par date (Y-m-d)
            $grouped = $games->groupBy(fn($g) => $g->created_at->format('Y-m-d'));

            $data = $grouped->map(function ($group) {
                return [
                    'label'      => $group->first()->created_at->format('Y-m-d'),
                    'total'      => $group->count(),
                    'wins'       => $group->where('result', 'won')->count(),
                    'busts'      => $group->where('player_bust', true)->count(),
                    'blackjacks' => $group->where('player_blackjack', true)->count(),
                    'total_won'  => $group->sum('payout'),
                    'total_bet'  => $group->sum('bet'),
                    'profit'     => $group->sum(fn($g) => $g->payout - $g->bet),
                ];
            })->values();
        }

        // Réponse JSON : on retourne à la fois stats globales et données graphiques
        return response()->json([
            'stats'   => $stats,
            'history' => $data,
        ]);
    }

    /**
     * Calcul des statistiques agrégées (haut de page)
     * @param \Illuminate\Support\Collection $games
     * @param \App\Models\User $user
     * @return array
     */
    private function calculateStatsForGames($games, $user)
    {
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

        return [
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
    }
}
