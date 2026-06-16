// database/migrations/2026_06_10_000009_create_disputes_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('raised_by')->constrained('users')->onDelete('cascade');
            $table->enum('reason', ['non_delivery', 'wrong_item', 'damaged_item', 'quality_issue', 'other']);
            $table->text('description');
            $table->enum('status', ['pending', 'under_review', 'resolved', 'rejected']);
            $table->text('resolution_notes')->nullable();
            $table->string('resolved_by')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('disputes');
    }
};