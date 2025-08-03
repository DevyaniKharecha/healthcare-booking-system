<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Js;

class AppointmentController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return auth()->user()->appointments()->with('professional')->get();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'healthcare_professional_id' => 'required|exists:healthcare_professionals,id',
            'appointment_start_time' => 'required|date|after:now',
            'appointment_end_time' => 'required|date|after:appointment_start_time',
        ]);

        $exists = Appointment::where('healthcare_professional_id', $validated['healthcare_professional_id'])
            ->where('appointment_start_time', '<', $validated['appointment_end_time'])
            ->where('appointment_end_time', '>', $validated['appointment_start_time'])
            ->where('status', 'booked')
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Slot not available'], 409);
        }

        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            $validated,
        ]);

        return response()->json($appointment, 201);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(int $id)
    {
        $appointment = Appointment::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if (Carbon::parse($appointment->appointment_start_time)->diffInHours(now()) < 24) {
            return response()->json(['message' => 'Cannot cancel within 24 hours'], 403);
        }

        $appointment->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Appointment cancelled']);
    }

    /***
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsCompleted(int $id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($appointment->status !== 'booked') {
            return response()->json(['message' => 'Only booked appointments can be completed'], 403);
        }

        $appointment->update(['status' => 'completed']);
        return response()->json(['message' => 'Appointment marked as completed.']);
    }

}

