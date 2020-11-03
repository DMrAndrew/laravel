<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MedicamentIngredient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredient_medicament', function($table)
        {

            $table->increments('id');
            $table->unsignedBigInteger ('medicament_id');
            $table->unsignedBigInteger ('ingredient_id');
            $table->timestamps();

//            $table->foreignId('medicament_id')
//                ->constrained('medicaments')
//                ->onDelete('cascade');
//            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredient_medicament');
    }
}
