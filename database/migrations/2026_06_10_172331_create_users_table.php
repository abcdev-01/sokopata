// database/migrations/2026_06_10_000002_create_users_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->enum('user_type', ['farmer', 'buyer', 'cooperative', 'admin']);
            $table->string('business_name')->nullable();
            $table->string('business_registration_number')->nullable();
            $table->string('national_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->default('Dar es Salaam');
            $table->string('profile_photo')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('phone_verified_at')->nullable();
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('total_transactions')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('users');
    }
};