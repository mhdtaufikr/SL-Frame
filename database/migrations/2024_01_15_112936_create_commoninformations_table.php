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
            $table->bigIncrements('CommonInfoID');
            $table->string('NoFrame', 255);
            $table->date('TglProd')->nullable();
            $table->string('Shift', 255)->nullable();
            $table->string('NamaQG', 255)->nullable();
            $table->string('PDI', 255)->nullable();
            $table->date('PDI_Date')->nullable();
            $table->string('Status', 50)->nullable();
            $table->integer('InspectionLevel')->nullable();
            $table->text('Remarks')->nullable()->nullable();
            $table->string('QualityStatus', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commoninformations');
    }
}
