<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function setOutlet(Request $request)
    {
        $outletId = $request->input('outlet_id');

        // Validate the outlet ID
        if (!$outletId) {
            return redirect()->back()->withErrors(['outlet_id' => 'Outlet ID is required']);
        }

        // Set the outlet in the session
        session(['outlet_id' => $outletId]);

        $request->user()->update(['outlet_id' => $outletId]);
        return response()->json(['message' => 'Session updated successfully']);
    }
}
