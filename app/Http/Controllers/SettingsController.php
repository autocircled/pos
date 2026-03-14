<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'currency_symbol' => Setting::get('currency_symbol', '৳'),
            'currency_code' => Setting::get('currency_code', 'BDT'),
            'shop_name' => Setting::get('shop_name', 'Stationery POS'),
            'shop_address' => Setting::get('shop_address', ''),
            'shop_phone' => Setting::get('shop_phone', ''),
            'tax_percentage' => Setting::get('tax_percentage', '0'),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'currency_symbol' => 'required|string|max:10',
            'currency_code' => 'required|string|max:10',
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'nullable|string|max:500',
            'shop_phone' => 'nullable|string|max:20',
            'tax_percentage' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::clearCache();

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
