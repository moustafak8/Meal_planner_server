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
        Schema::create('pantry-history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pantry_item_id')->constrained('pantry-item')->onDelete('cascade');
            $table->float('quantity_changed');
            $table->string('change_type'); // e.g., 'added', 'removed', 'consumed'
             $table->string('reason')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
