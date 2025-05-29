<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\SessionData;
use Illuminate\Http\Request;

class CalenderController extends Controller
{
    public function calender($id){

        $doctor= Doctor::where('id', $id)->first();
        return view ('appointments.calender', compact('doctor'));
    }

    public function getCalendarAppointments(Request $request)
    {
        $doctorId = $request->input('doctor_id');

        $appointments = Appointment::where('doctor_id', $doctorId)
            ->whereNotNull('time_from')
            ->get();

        $sessions = SessionData::where('doctor_id', $doctorId)
            ->whereNotNull('session_time')
            ->get();

        $events = [];

        // Helper function to format time like "10:30a" or "03:45p"
        $formatTime = function($time) {
            $formatted = \Carbon\Carbon::parse($time)->format('h:ia'); // e.g. 10:30am
            // return time with am/pm shortened to a/p (remove the 'm')
            return substr($formatted, 0, -1); // remove last 'm'
        };

        // Appointments (A)
        foreach ($appointments as $appointment) {
            $startTime = $formatTime($appointment->time_from);
            $endTime = $formatTime($appointment->time_to);

            $events[] = [
                'title' => ' - ' . $endTime . ' A',
                'start' => $appointment->appointment_date . 'T' . \Carbon\Carbon::parse($appointment->time_from)->format('H:i:s'),
                'end'   => $appointment->appointment_date . 'T' . \Carbon\Carbon::parse($appointment->time_to)->format('H:i:s'),
                'color' => '#007bff'
            ];
        }

        // Sessions (S)
        foreach ($sessions as $session) {
            $start = \Carbon\Carbon::parse($session->session_time);
            $end = $start->copy()->addHour();

            $events[] = [
                'title' => ' - ' . $formatTime($end) . ' S',
                'start' => $session->session_date . 'T' . $start->format('H:i:s'),
                'end'   => $session->session_date . 'T' . $end->format('H:i:s'),
                'color' => '#28a745'
            ];
        }

        return response()->json($events);
    }







public function getDaySchedule($date, Request $request)
{
    $doctorId = $request->input('doctor_id');

    $appointments = Appointment::where('doctor_id', $doctorId)
        ->where('appointment_date', $date)
        ->get(['time_from', 'time_to']);

    $sessions = SessionData::where('doctor_id', $doctorId)
        ->whereDate('session_date', $date)
        ->get(['session_time']);

    $appointmentTimes = $appointments->map(function ($a) {
        return ['time' => $a->time_from . ' - ' . $a->time_to];
    })->toArray();

    $sessionTimes = $sessions->map(function ($s) {
        return ['time' => $s->session_time];
    })->toArray();

    return response()->json(array_merge($appointmentTimes, $sessionTimes));
}



}
