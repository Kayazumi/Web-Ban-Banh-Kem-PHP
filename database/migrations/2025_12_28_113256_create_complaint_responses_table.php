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
        Schema::create('complaint_responses', function (Blueprint $table) {
            $table->id('ResponseID'); // Match existing primary key
            $table->unsignedBigInteger('complaint_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('user_type', ['customer', 'staff', 'admin']);
            $table->text('content');
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->foreign('complaint_id')->references('ComplaintID')->on('complaints')->onDelete('cascade');
            $table->foreign('user_id')->references('UserID')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_responses');
    }
};
