<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'pesapal_transaction_tracking_id')) {
                $table->string('pesapal_transaction_tracking_id')->nullable()->after('payment_transaction_id');
            }
            if (!Schema::hasColumn('orders', 'pesapal_payment_status')) {
                $table->string('pesapal_payment_status')->nullable()->after('pesapal_transaction_tracking_id');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pesapal_transaction_tracking_id', 'pesapal_payment_status']);
        });
    }
};