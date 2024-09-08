<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('discount')->default(0);
            $table->string('color')->nullable();
            $table->timestamps();
        });

        // Optionally, add membership_type_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('membership_type_id')->default(1); 
            $table->foreign('membership_type_id')->references('id')->on('membership_types');
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
            $table->dropForeign(['membership_type_id']);
            $table->dropColumn('membership_type_id');
        });

        Schema::dropIfExists('membership_types');
    }
}
