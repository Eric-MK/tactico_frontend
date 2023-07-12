<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shortlists', function (Blueprint $table) {
            $table->id();
            $table->string('player_name');
            $table->string('position');
            $table->string('competition');
            $table->integer('age');
            $table->string('player_type')->nullable();
            $table->timestamps();

            $table->foreignId('user_id')
        ->constrained('users')
        ->constrained()
        ->onDelete('cascade'); // Set cascade delete behavior
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shortlists');
    }
}
