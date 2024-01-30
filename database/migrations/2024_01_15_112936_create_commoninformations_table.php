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
            $table->date('TglProd')->nullable();
            $table->string('Shift')->nullable();
            $table->string('NamaQG')->nullable();
            $table->string('PDI')->nullable();
            $table->date('PDI_Date')->nullable();
            $table->string('Status', 50);
            $table->integer('InspectionLevel')->default(1); // Added InspectionLevel column
            $table->text('Remarks')->nullable();
            $table->string('QualityStatus', 50)->default('Good'); // Added QualityStatus column
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commoninformations');
    }
}
