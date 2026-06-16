<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Check if columns exist before adding
            if (!Schema::hasColumn('reviews', 'helpful_count')) {
                $table->integer('helpful_count')->default(0)->after('comment');
            }
            if (!Schema::hasColumn('reviews', 'verified_purchase')) {
                $table->boolean('verified_purchase')->default(false)->after('helpful_count');
            }
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['helpful_count', 'verified_purchase']);
        });
    }
};