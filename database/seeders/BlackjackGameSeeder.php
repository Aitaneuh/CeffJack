<?php
namespace Database\Seeders;

use App\Models\BlackjackGame;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BlackjackGameSeeder extends Seeder
{
    private $suits = ['_of_spades', '_of_hearts', '_of_diamonds', '_of_clubs'];
    private $ranks = [
        '2'  => 2, '3'     => 3, '4'      => 4, '5'     => 5,
        '6'  => 6, '7'     => 7, '8'      => 8, '9'     => 9,
        '10' => 10, 'jack' => 10, 'queen' => 10, 'king' => 10, 'ace' => 11,
    ];

    public function run()
    {
        $user = User::first(); // À adapter si tu veux plusieurs users

        $startDate = Carbon::now()->subMonth();
        $endDate   = Carbon::now();

        for ($date = $startDate; $date->lessThanOrEqualTo($endDate); $date->addDay()) {
            $gamesCount = rand(10, 100);

            for ($i = 0; $i < $gamesCount; $i++) {
                $deck = $this->generateShuffledDeck();

                $player = [array_pop($deck), array_pop($deck)];
                $dealer = [array_pop($deck)];

                $playerTotal = $this->calculateTotal($player);
                $dealerTotal = $this->calculateTotal($dealer);

                $extraCards = rand(0, 3);
                for ($j = 0; $j < $extraCards; $j++) {
                    $player[]    = array_pop($deck);
                    $playerTotal = $this->calculateTotal($player);
                    if ($playerTotal > 21) {
                        break;
                    }

                }

                while ($dealerTotal < 17) {
                    $dealer[]    = array_pop($deck);
                    $dealerTotal = $this->calculateTotal($dealer);
                }

                if ($playerTotal > 21) {
                    $result = 'lost';
                } elseif ($dealerTotal > 21) {
                    $result = 'won';
                } elseif ($playerTotal > $dealerTotal) {
                    $result = 'won';
                } elseif ($playerTotal < $dealerTotal) {
                    $result = 'lost';
                } else {
                    $result = 'draw';
                }

                                                         // 🔧 Simulation du bet
                $bet = round(rand(100, 10000) / 100, 2); // entre $1.00 et $100.00

                // 🔧 Détection de blackjack
                $isBlackjack = count($player) === 2 && $playerTotal === 21;

                // 🔧 Calcul du payout
                if ($result === 'won') {
                    $payout = $isBlackjack ? round($bet * 2.5, 2) : round($bet * 2, 2);
                } elseif ($result === 'draw') {
                    $payout = $bet;
                } else {
                    $payout = 0;
                }

                // 🔧 Calcul du profit
                $profit = round($payout - $bet, 2);

                BlackjackGame::create([
                    'user_id'          => $user ? $user->id : null,
                    'player'           => $player,
                    'dealer'           => $dealer,
                    'player_total'     => $playerTotal,
                    'dealer_total'     => $dealerTotal,
                    'result'           => $result,
                    'player_blackjack' => $isBlackjack,
                    'dealer_blackjack' => (count($dealer) === 2 && $dealerTotal === 21),
                    'player_bust'      => ($playerTotal > 21),
                    'dealer_bust'      => ($dealerTotal > 21),

                    // 🆕 Ajout des champs financiers
                    'bet'              => $bet,
                    'payout'           => $payout,
                    'profit'           => $profit,

                    'created_at'       => $date->copy()->addSeconds(rand(0, 86400)),
                    'updated_at'       => $date->copy()->addSeconds(rand(0, 86400)),
                ]);
            }
        }
    }

    private function generateShuffledDeck()
    {
        $deck = [];
        foreach ($this->suits as $suit) {
            foreach ($this->ranks as $rank => $value) {
                $deck[] = ['card' => $rank . $suit, 'value' => $value];
            }
        }
        shuffle($deck);
        return $deck;
    }

    private function calculateTotal(array $hand)
    {
        $total = 0;
        $aces  = 0;
        foreach ($hand as $card) {
            $total += $card['value'];
            if ($card['value'] === 11) {
                $aces++;
            }
        }
        while ($total > 21 && $aces > 0) {
            $total -= 10;
            $aces--;
        }
        return $total;
    }
}
