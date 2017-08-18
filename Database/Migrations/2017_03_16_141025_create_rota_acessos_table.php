<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRotaAcessosTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rota_acessos', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->string('text');
            $table->string('rota');
            $table->string('icon')->nullable();
            $table->string('prioridade')->nullable()->default(99);
            $table->boolean('disabled')->default(false);
            $table->enum('ambiente', ['admin', 'adminsite']);
            $table->softDeletes();
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rota_acessos');
	}

}
