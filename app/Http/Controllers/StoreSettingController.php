<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StoreSetting;

class StoreSettingController extends Controller
{
    public function create() {
        return view('storesetting.create');
    }

    public function edit($id) {
        $store_setting = StoreSetting::find($id);

        return view('storesetting.edit', ['store_setting' => $store_setting]);
    }

    public function store(Request $request) {
        $store_setting = new StoreSetting;

        $store_setting->store_id = session('store_id');
        $store_setting->start_time = $request->start_time;
        $store_setting->end_time = $request->end_time;
        $store_setting->term = $request->term;

        $store_setting->save();

        return redirect()->route('user.index');
    }

    public function update(Request $request, $id) {
        $store_setting = StoreSetting::find($id);
        
        $store_setting->store_id = session('store_id');
        $store_setting->start_time = $request->start_time;
        $store_setting->end_time = $request->end_time;
        $store_setting->term = $request->term;

        $store_setting->save();

        return redirect()->route('user.index');
    }
}
