// database/migrations/2026_06_10_000008_create_delivery_tracking_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('rider_name')->nullable();
            $table->string('rider_phone')->nullable();
            $table->string('current_location')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['picked_up', 'in_transit', 'nearby', 'delivered']);
            $table->timestamp('status_updated_at');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('delivery_tracking');
    }
};