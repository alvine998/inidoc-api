<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TbUserAktifitas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_user_aktifitas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id');
            $table->integer('id_user');
            $table->string('bio');
            $table->string('tempat_lahir');
            $table->string('alamat');
            $table->string('hobby_olahraga');
            $table->string('hobby_musik');
            $table->string('hobby_makanan');
            $table->string('hobby_film');
            $table->nullableMorphs('hobby_buku');
            $table->nullableUuidMorphs('hobby_travel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
