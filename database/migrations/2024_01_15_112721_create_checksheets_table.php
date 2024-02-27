<?php

// database/migrations/YYYY_MM_DD_create_checksheets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

class CreateChecksheetsTable extends Migration
{
    public function up()
    {
        Schema::create('checksheets', function (Blueprint $table) {
            $table->id();
            $table->integer('CommonInfoID');
            $table->string('ItemCheck');
            $table->integer('checkGroup');
            $table->string('FindingQG', 255)->nullable();
            $table->string('RepairQG', 255)->nullable();
            $table->string('FindingPDI', 45)->nullable();
            $table->string('RepairPDI', 45)->nullable();
            $table->text('Remarks')->nullable()->nullable();
            $table->string('Problem')->nullable();
            $table->string('Status', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('checksheets');
    }
}

