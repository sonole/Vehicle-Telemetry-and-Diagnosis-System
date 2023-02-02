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
        Schema::create('packets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sn', 20);
            $table->bigInteger('counter')->unique();;
            $table->dateTime('datetime')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->smallInteger('altitude')->nullable();
            $table->smallInteger('speed')->nullable();
            $table->smallInteger('course')->nullable();
            $table->tinyInteger('satellites')->nullable();
            $table->text('accelerometer')->nullable();
            $table->integer('service1pid00')->nullable();
            $table->text('pids')->nullable();
            $table->string('dtc_status')->nullable();
            $table->integer('crc')->nullable();
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
        Schema::dropIfExists('packets');
    }
};
