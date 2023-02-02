<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_entries', function (Blueprint $table) {
            $table->id();
            $table->timestamp('entered_at');
            $table->timestamp('exited_at')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->integer('seconds_worked')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_entries');
    }
}
