<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlackjackGame extends Model
{
    protected $fillable = [
        'user_id',
        'player',
        'dealer',
        'player_total',
        'dealer_total',
        'result',
        'player_blackjack',
        'dealer_blackjack',
        'player_bust',
        'dealer_bust',
        'bet',
        'payout',
        'profit',
    ];

    protected $casts = [
        'player'           => 'array',
        'dealer'           => 'array',
        'player_blackjack' => 'boolean',
        'dealer_blackjack' => 'boolean',
        'player_bust'      => 'boolean',
        'dealer_bust'      => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
