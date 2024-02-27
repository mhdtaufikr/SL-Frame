<?php
// database/migrations/YYYY_MM_DD_create_itemcheckgroups_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCheckGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('itemcheckgroups', function (Blueprint $table) {
            $table->id('GroupID');
            $table->string('index');
            $table->integer('CheckGroup');
            $table->string('ItemCheck');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('itemcheckgroups');
    }
}
