<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->unsignedBigInteger('authorizer_user_id')->nullable();
            $table->date('date');
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('leave_type_id');
            $table->enum('half_type', ['first', 'second'])->nullable();
            $table->decimal('day', 5, 2)->default(0);
            $table->string('reason', 250);
            $table->mediumText('remarks', 250)->nullable();
            $table->boolean('status')->default(0)->comment('0 review, 1 approved, 2 rejected');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
