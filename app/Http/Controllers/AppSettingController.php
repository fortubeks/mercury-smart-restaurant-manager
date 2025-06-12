<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    public function index()
    {
        return theme_view('settings.index');
    }
    public function showAppSettingsForm()
    {
        //if restaurant does not have AppSetting create one and send it to the view
        // Check if the restaurant has an existing AppSetting
        $appSetting = restaurant()->appSetting;

        // If the restaurant does not have an AppSetting, create one
        if (!$appSetting) {
            $appSetting = new AppSetting();
            $appSetting->restaurant_id = restaurantId();
            $appSetting->save();
        }

        // Send the AppSetting to the view
        return theme_view('settings.app-settings', compact('appSetting'));
    }

    public function updateAppSettings(Request $request)
    {
        $restaurant = restaurant(); // Ensure $restaurant is defined
        $settings = $restaurant->appSetting;

        // Mass update with boolean casting
        $settings->update([
            'manage_stock' => $request->boolean('manage_stock'),
            'include_tax' => $request->boolean('include_tax'),
        ]);

        // Sync selected module IDs, default to empty array if none sent
        $restaurant->modules()->sync($request->input('modules', []));

        return back()->with('success_message', 'App Settings Updated Successfully');
    }
}
