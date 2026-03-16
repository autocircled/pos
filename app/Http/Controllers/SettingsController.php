<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'timezone' => Setting::get('timezone', 'Asia/Dhaka'),
        ];

        $paymentMethods = $this->getPaymentMethodsForView();

        return view('settings.index', compact('settings', 'paymentMethods'));
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
            'timezone' => ['required', 'string', 'max:50', Rule::in(timezone_identifiers_list())],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        // Payment methods: array of {code, name}
        if ($request->has('payment_methods')) {
            $methods = [];
            $codes = $request->input('payment_methods.code', []);
            $names = $request->input('payment_methods.name', []);
            foreach ($codes as $i => $code) {
                $code = preg_replace('/[^a-z0-9_]/', '_', strtolower(trim($code)));
                $name = trim($names[$i] ?? '');
                if ($code !== '' && $name !== '') {
                    $methods[] = ['code' => $code, 'name' => $name];
                }
            }
            if (count($methods) > 0) {
                Setting::set('payment_methods', json_encode($methods));
            }
        }

        Setting::clearCache();

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    private function getPaymentMethodsForView(): array
    {
        if (old('payment_methods.code') && old('payment_methods.name')) {
            $codes = old('payment_methods.code');
            $names = old('payment_methods.name');
            $out = [];
            foreach ($codes as $i => $code) {
                $out[] = ['code' => $code ?? '', 'name' => $names[$i] ?? ''];
            }
            return $out;
        }
        return Setting::getPaymentMethods();
    }
}
