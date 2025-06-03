<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlackjackGame;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class BlackjackController extends Controller
{
    public function index(Request $request)
    {
        // Initialisation d'une partie si aucune session n'existe
        if (! session()->has('blackjack')) {
            $this->resetGame();
        }

        return view('blackjack', [
            'game'    => session('blackjack'),
            'lastBet' => $this->getLastBet(),
        ]);
    }

    public function hit(Request $request)
    {
        $game             = session('blackjack');
        $card             = array_pop($game['deck']);
        $game['player'][] = $card;
        $game['state']    = 'playing';

        $game['player_total'] = $this->calculateTotal($game['player']);

        if ($game['player_total'] > 21) {
            $game['state'] = 'lost';

            $user = Auth::user();
            $bet  = $game['bet'] ?? 0;

            BlackjackGame::create([
                'user_id'          => $user?->id,
                'player'           => $game['player'],
                'dealer'           => $game['dealer'],
                'player_total'     => $game['player_total'],
                'dealer_total'     => $game['dealer_total'],
                'result'           => $game['state'],
                'player_blackjack' => count($game['player']) === 2 && $game['player_total'] === 21,
                'dealer_blackjack' => count($game['dealer']) === 2 && $game['dealer_total'] === 21,
                'player_bust'      => true,
                'dealer_bust'      => false,
                'bet'              => $bet,
                'payout'           => 0,
                'profit'           => -$bet,
            ]);
        }

        session(['blackjack' => $game]);
        return redirect()->route('blackjack');
    }

    public function stand(Request $request)
    {
        $game = session('blackjack');

        // Le croupier tire jusqu'à 17 ou plus
        while ($this->calculateTotal($game['dealer']) < 17) {
            $card             = array_pop($game['deck']);
            $game['dealer'][] = $card;
        }

        $game['dealer_total'] = $this->calculateTotal($game['dealer']);

        $player = $game['player_total'];
        $dealer = $game['dealer_total'];

        if ($dealer > 21 || $player > $dealer) {
            $game['state'] = 'won';
        } elseif ($player < $dealer) {
            $game['state'] = 'lost';
        } else {
            $game['state'] = 'draw';
        }

        $user = Auth::user();

        $payout = 0;
        $profit = 0;

        if ($game['state'] === 'won') {
            $payout = $game['bet'] * 2;
            $profit = $game['bet'];
            $user->balance += $payout;
            $user->save();
        } elseif ($game['state'] === 'draw') {
            $payout = $game['bet'];
            $profit = 0;
            $user->balance += $payout;
            $user->save();
        } else {
            $payout = 0;
            $profit = -$game['bet'];
        }

        BlackjackGame::create([
            'user_id'          => $user?->id,
            'player'           => $game['player'],
            'dealer'           => $game['dealer'],
            'player_total'     => $player,
            'dealer_total'     => $dealer,
            'result'           => $game['state'],
            'player_blackjack' => count($game['player']) === 2 && $player === 21,
            'dealer_blackjack' => count($game['dealer']) === 2 && $dealer === 21,
            'player_bust'      => $player > 21,
            'dealer_bust'      => $dealer > 21,
            'bet'              => $game['bet'],
            'payout'           => $payout,
            'profit'           => $profit,
        ]);

        session(['blackjack' => $game]);
        return redirect()->route('blackjack');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'bet' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        if ($user->balance < $request->bet) {
            return back()->withErrors(['bet' => 'Not enough balance']);
        }

        $user->balance -= $request->bet;
        $user->save();

        $this->resetGame($request->bet);

        return redirect()->route('blackjack');
    }

    private function resetGame($bet = 0)
    {
        $deck   = $this->generateShuffledDeck();
        $player = [array_pop($deck), array_pop($deck)];
        $dealer = [array_pop($deck)];

        session([
            'blackjack' => [
                'deck'         => $deck,
                'player'       => $player,
                'dealer'       => $dealer,
                'player_total' => $this->calculateTotal($player),
                'dealer_total' => $this->calculateTotal($dealer),
                'state'        => 'playing',
                'bet'          => $bet,
            ],
        ]);
    }

    private function generateShuffledDeck()
    {
        $suits = ['_of_spades', '_of_hearts', '_of_diamonds', '_of_clubs'];
        $ranks = [
            '2'  => 2, '3'     => 3, '4'      => 4, '5'     => 5,
            '6'  => 6, '7'     => 7, '8'      => 8, '9'     => 9,
            '10' => 10, 'jack' => 10, 'queen' => 10, 'king' => 10, 'ace' => 11,
        ];

        $deck = [];
        foreach ($suits as $suit) {
            foreach ($ranks as $rank => $value) {
                $deck[] = ['card' => $rank . $suit, 'value' => $value];
            }
        }

        shuffle($deck);
        return $deck;
    }

    private function calculateTotal($hand)
    {
        $total = 0;
        $aces  = 0;

        foreach ($hand as $card) {
            $total += $card['value'];
            if ($card['value'] == 11) {
                $aces++;
            }
        }

        // Ajustement des As si le total dépasse 21
        while ($total > 21 && $aces > 0) {
            $total -= 10;
            $aces--;
        }

        return $total;
    }

    public function history()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $games = \App\Models\BlackjackGame::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $history = $games->map(function ($game) {
            return [
                'date'   => $game->created_at,
                'result' => $game->result,
                'dealer' => $game->dealer,
                'player' => $game->player,
            ];
        });

        return view('history', compact('games'));
    }

    public function resetProgress(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => __('The password is incorrect.'),
            ])->errorBag('progressReset');
        }

        BlackjackGame::where('user_id', $request->user()->id)->delete();

        return redirect()->route('profile.edit')->with('status', 'Your Blackjack progress has been reset.');
    }

    public function getLastBet(): int
    {
        return \App\Models\BlackjackGame::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->value('bet') ?? 1000;
    }

}
