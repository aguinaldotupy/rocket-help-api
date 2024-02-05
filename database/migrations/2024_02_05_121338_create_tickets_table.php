<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('status', 50); // open, closed, pending
            $table->string('title');
            $table->longText('description');
            $table->foreignUuid('created_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('ticket_comments', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ticket_id')
                ->constrained('tickets')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->longText('comment');
            $table->foreignUuid('commented_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_comments');
        Schema::dropIfExists('tickets');
    }
};
