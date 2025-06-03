<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('blackjack_games', function (Blueprint $table) {
        $table->id();
        $table->json('player');
        $table->json('dealer');
        $table->integer('player_total');
        $table->integer('dealer_total');
        $table->enum('result', ['won', 'lost', 'draw']);
        $table->boolean('player_blackjack')->default(false);
        $table->boolean('dealer_blackjack')->default(false);
        $table->boolean('player_bust')->default(false);
        $table->boolean('dealer_bust')->default(false);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blackjack_games');
    }
};
