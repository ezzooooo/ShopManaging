<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use App\StoreSetting;

class TimetableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Reservation::where('store_id',session('store_id'))->orderBy('start_time')->get();
        foreach($results as $result) {
            $result->name = $result->customer->name;
        }

        return view('timetable.index', ['items' => $results]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($date)
    {
        $store_setting = StoreSetting::where('store_id', session('store_id'))->first();
        if($store_setting == null) {
            return redirect()->route('storesetting.create');
        }

        $all_items = Reservation::where('store_id',session('store_id'))->where('date', $date)->orderBy('start_time')->whereNotIn('stat',["예약확인 대기"])->get();
        $wait_items = Reservation::where('store_id',session('store_id'))->where('date', $date)->where('stat', "예약확정 대기")->get();
        $store_setting = StoreSetting::where('store_id', session('store_id'))->first();
        return view('timetable.show', ["all_items" => $all_items, "wait_items" => $wait_items, "date" => $date, "store_setting" => $store_setting]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
