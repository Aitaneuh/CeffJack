<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blackjack_games', function (Blueprint $table) {
            // Ajouter les nouvelles colonnes
            $table->integer('bet')->default(0)->after('dealer_bust');
            $table->integer('payout')->default(0)->after('bet');
            $table->integer('profit')->default(0)->after('payout');

            // Supprimer l'ancienne colonne winnings
            $table->dropColumn('winnings');
        });
    }

    public function down()
    {
        Schema::table('blackjack_games', function (Blueprint $table) {
            // Remettre la colonne winnings en cas de rollback
            $table->integer('winnings')->default(0)->after('user_id');

            // Supprimer les nouvelles colonnes
            $table->dropColumn(['bet', 'payout', 'profit']);
        });
    }
};
