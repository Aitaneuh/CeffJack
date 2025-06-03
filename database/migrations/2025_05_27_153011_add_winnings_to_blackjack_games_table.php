<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWinningsToBlackjackGamesTable extends Migration
{
    public function up()
    {
        Schema::table('blackjack_games', function (Blueprint $table) {
            $table->integer('winnings')->default(0)->after('user_id');
            // Si tu préfères float/décimal, adapte le type en fonction
        });
    }

    public function down()
    {
        Schema::table('blackjack_games', function (Blueprint $table) {
            $table->dropColumn('winnings');
        });
    }
}
