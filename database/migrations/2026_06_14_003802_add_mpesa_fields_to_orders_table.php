<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'mpesa_checkout_request_id')) {
                $table->string('mpesa_checkout_request_id')->nullable()->after('payment_transaction_id');
            }
            if (!Schema::hasColumn('orders', 'mpesa_result_code')) {
                $table->integer('mpesa_result_code')->nullable()->after('mpesa_checkout_request_id');
            }
            if (!Schema::hasColumn('orders', 'mpesa_result_desc')) {
                $table->text('mpesa_result_desc')->nullable()->after('mpesa_result_code');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['mpesa_checkout_request_id', 'mpesa_result_code', 'mpesa_result_desc']);
        });
    }
};