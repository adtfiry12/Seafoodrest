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
        Schema::create('menu', function (Blueprint $table) {
          $table->id();
        $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
        $table->string('nama_menu');
        $table->text('deskripsi')->nullable();
        $table->string('gambar')->nullable();
        $table->integer('harga')->default(0);
        $table->integer('stok')->default(0);
        $table->string('slug')->unique();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
