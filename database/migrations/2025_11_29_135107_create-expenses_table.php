<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('expenses', function (Blueprint $table) {
           $table->id();
           $table->foreignId('household_id')->constrained('households')->onDelete('cascade');
           $table->foreignId('created_by')->constrained('_users__')->onDelete('cascade');
           $table->string('title');
           $table->decimal('amount_spent', 10, 2);
           $table->string('Currency');
           $table->string('category');
           $table->string('notes')->nullable();
           $table->date('expense_date');
           $table->timestamps();
       });
    }
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
