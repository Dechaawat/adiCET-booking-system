<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'equipment_id',
        'start_time',
        'end_time',
        'status',
        'reason',
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // ความสัมพันธ์กับ Equipment
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
