<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function setShift(Request $request)
    {
        // Validate the shift date
        $request->validate([
            'shift_date' => 'required|date',
        ]);
        // Update the user's current shift date
        $shift_date = $request->input('shift_date');
        $request->user()->update(['current_shift' => $shift_date]);
    }
}
