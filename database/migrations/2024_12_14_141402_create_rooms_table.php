<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อห้อง
            $table->enum('type', ['room', 'meeting', 'classroom']); // ประเภทห้อง
            $table->integer('capacity'); // ความจุ
            $table->text('description')->nullable(); // รายละเอียดเพิ่มเติม
            $table->timestamps(); // created_at, updated_at
        });

        // เพิ่มข้อมูลตัวอย่าง
        DB::table('rooms')->insert([
            [
                'name' => 'Meeting Room 1',
                'type' => 'meeting',
                'capacity' => 10,
                'description' => 'Room for small meetings.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Classroom A',
                'type' => 'classroom',
                'capacity' => 30,
                'description' => 'Standard classroom.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Conference Hall',
                'type' => 'room',
                'capacity' => 100,
                'description' => 'Large hall for conferences and events.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
}
