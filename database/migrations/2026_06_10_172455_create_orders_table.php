// database/migrations/2026_06_10_000004_create_orders_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('commission', 12, 2)->default(0)->comment('5-8% platform fee');
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', [
                'pending', 'payment_pending', 'payment_confirmed', 
                'processing', 'dispatched', 'delivered', 
                'completed', 'cancelled', 'disputed'
            ])->default('pending');
            $table->enum('payment_method', [
                'mpesa', 'tigo_pesa', 'airtel_money', 
                'halopesa', 'pesapal', 'bank_transfer'
            ]);
            $table->string('payment_transaction_id')->nullable();
            $table->boolean('payment_released')->default(false);
            $table->timestamp('payment_released_at')->nullable();
            $table->text('delivery_address');
            $table->timestamp('delivered_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'buyer_id', 'supplier_id']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};