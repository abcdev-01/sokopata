// database/migrations/2026_06_10_000007_create_subscriptions_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['buyer_premium', 'supplier_premium']);
            $table->enum('status', ['active', 'expired', 'cancelled']);
            $table->decimal('amount', 10, 2);
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};