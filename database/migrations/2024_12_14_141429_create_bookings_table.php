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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ผู้จอง
            $table->foreignId('room_id')->nullable()->constrained('rooms')->onDelete('cascade'); // ห้องที่จอง
            $table->foreignId('equipment_id')->nullable()->constrained('equipment')->onDelete('cascade'); // อุปกรณ์ที่จอง
            $table->datetime('start_time'); // เวลาเริ่มต้น
            $table->datetime('end_time'); // เวลาสิ้นสุด
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // สถานะ
            $table->text('reason')->nullable(); // เหตุผลที่จอง
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
