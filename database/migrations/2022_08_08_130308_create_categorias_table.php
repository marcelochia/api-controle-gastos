<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        DB::table('categorias')->insert(['nome' => 'Alimentação']);
        DB::table('categorias')->insert(['nome' => 'Saúde']);
        DB::table('categorias')->insert(['nome' => 'Moradia']);
        DB::table('categorias')->insert(['nome' => 'Transporte']);
        DB::table('categorias')->insert(['nome' => 'Educação']);
        DB::table('categorias')->insert(['nome' => 'Lazer']);
        DB::table('categorias')->insert(['nome' => 'Imprevistos']);
        DB::table('categorias')->insert(['nome' => 'Outras']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias');
    }
};
