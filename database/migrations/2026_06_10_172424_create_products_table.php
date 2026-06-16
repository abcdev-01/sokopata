// database/migrations/2026_06_10_000003_create_products_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->enum('category', ['vegetables', 'fruits', 'grains', 'dairy', 'meat', 'fish', 'processed']);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('unit')->comment('kg, bunch, liter, piece');
            $table->decimal('quantity', 10, 2);
            $table->string('location');
            $table->string('image_url')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('min_order_quantity')->default(1);
            $table->integer('views_count')->default(0);
            $table->timestamps();
            
            $table->index(['category', 'is_available']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('products');
    }
};