<?php

namespace App\Http\Controllers;

use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WorkingHourController extends Controller
{
    public function getWorkingHours(Request $request){
        $date = $request->query('date');
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $workingHours = WorkingHour::where('day_of_week', $dayOfWeek)->firstOrFail();

        return response()->json([
            'working_hours' => [
                'start_time' => $workingHours->start_time,
                'end_time' => $workingHours->end_time,
                'break_start' => $workingHours->break_time_start,
                'break_end' => $workingHours->break_time_end,
                'is_closed' => $workingHours->is_closed,
            ]
        ]);

    }
}
