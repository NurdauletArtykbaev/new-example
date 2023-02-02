<?php

use App\Helpers\DeviceTokenHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProviderToDeviceTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_tokens', function (Blueprint $table) {
            $table->string('driver')->default(DeviceTokenHelper::DRIVER_FIREBASE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_tokens', function (Blueprint $table) {
            //
        });
    }
}
