<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSheetSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheet_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sheet_id');
            $table->string('kolom');
            $table->integer('row');
            $table->string('validation_type')->nullable();
            $table->string('color')->nullable();
            $table->string('validation')->nullable();
            $table->text('query_value')->nullable();
            $table->integer('sequence')->default(0);
            $table->integer('editable')->default(1);
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
        Schema::dropIfExists('sheet_settings');
    }
}
