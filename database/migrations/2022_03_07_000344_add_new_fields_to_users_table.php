<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('users', function (Blueprint $table) {
            /*$table->string('username', 15)->unique()->after('id');
            $table->string('lastname', 15)->unique()->after('username');
            $table->string('firstname', 15)->unique()->after('lastname');
            $table->string('contact')->nullable()->after('email');
            $table->date('birthday')->after('contact');
            $table->tinyInteger('status')->default(1)->after('birthday');
            */
            $table->tinyInteger('status')->default(1);
            $table->integer('perId')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
