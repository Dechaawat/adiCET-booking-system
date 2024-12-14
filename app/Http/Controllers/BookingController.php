<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Equipment;   
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::query();
    
        // กรองตามชื่อห้อง
        if ($request->filled('room')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->room . '%');
            });
        }
    
        // กรองตามสถานะ
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        $bookings = $query->with(['room', 'equipment'])->paginate(10);
    
        return view('bookings.index', compact('bookings'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::all();
        $equipment = Equipment::all();
        return view('bookings.create', compact('rooms', 'equipment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'room_id' => 'nullable|exists:rooms,id',
        'equipment_id' => 'nullable|exists:equipment,id',
        'start_time' => 'required|date|before:end_time',
        'end_time' => 'required|date|after:start_time',
        'reason' => 'nullable|string',
    ]);

    // เพิ่ม user_id ลงในข้อมูล
    $data = $request->all();
    $data['user_id'] = Auth::id();

    Booking::create($data);

    return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
}
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $rooms = Room::all(); // ดึงข้อมูลห้องทั้งหมด
        $equipment = Equipment::all(); // ดึงข้อมูลอุปกรณ์ทั้งหมด
        return view('bookings.edit', compact('booking', 'rooms', 'equipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'room_id' => 'nullable|exists:rooms,id',
            'equipment_id' => 'nullable|exists:equipment,id',
            'start_time' => 'required|date|before:end_time',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:pending,approved,rejected',
            'reason' => 'nullable|string',
        ]);
    
        $booking->update($request->all());
    
        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete(); // ลบข้อมูลในฐานข้อมูล
    
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully!');
    }
    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'approved']);
    
        // ส่ง Notification
        $booking->user->notify(new BookingStatusNotification($booking, 'approved'));
    
        return redirect()->route('bookings.index')->with('success', 'Booking approved successfully!');
    }

    public function reject(Booking $booking)
    {
        $booking->update(['status' => 'rejected']);
    
        // ส่ง Notification
        $booking->user->notify(new BookingStatusNotification($booking, 'rejected'));
    
        return redirect()->route('bookings.index')->with('success', 'Booking rejected successfully!');
    }
}
