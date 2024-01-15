<?php
// database/migrations/YYYY_MM_DD_create_commoninformations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommonInformationsTable extends Migration
{
    public function up()
    {
        Schema::create('commoninformations', function (Blueprint $table) {
            $table->id('CommonInfoID');
            $table->string('NoFrame');
            $table->date('TglProd');
            $table->string('Shift');
            $table->string('NamaQG');
            $table->string('PDI');
            $table->date('PDI_Date');
            $table->string('itemcheck');
            $table->string('Status', 50);
            $table->text('Remarks');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commoninformations');
    }
}

