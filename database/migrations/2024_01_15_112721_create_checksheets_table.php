<?php

// database/migrations/YYYY_MM_DD_create_checksheets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecksheetsTable extends Migration
{
    public function up()
    {
        Schema::create('checksheets', function (Blueprint $table) {
            $table->id();
            $table->integer('CommonInfoID');
            $table->string('ItemCheck');
            $table->string('FindingQC');
            $table->string('RepairQC');
            $table->string('FindingPDI');
            $table->string('RepairPDI');
            $table->text('RemarksQG');
            $table->text('RemarksPDI');
            $table->string('Problem');
            $table->string('Status', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('checksheets');
    }
}

