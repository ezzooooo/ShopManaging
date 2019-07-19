<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Customer;

class CustomerController extends Controller
{
    public function index() {
        if(Auth::check()) {

        $customers = Customer::where('store_id', session('store_id'))->get();
        $total_deposit = 0;

        foreach ($customers as $customer) {
            $total_deposit += $customer->deposit;
        }

        return view('customer.index', ['customers'=>$customers, 'total_deposit'=>$total_deposit]);
        }
        else {
            return redirect()->route('login');
        }
    }

    public function create() {
        return view('customer.create');
    }

    public function store(Request $request) {
        $customer = new Customer;

        //핸드폰번호 확인
        $hp = preg_replace("/[^0-9]/", "", $request->phone);
        if(!(preg_match("/^01[0-9]{8,9}$/", $hp))) {
            echo "<script>alert('핸드폰 번호를 형식에 맞게 입력해주세요.');
                    history.back();</script>";
            return;
        }

        $customer->store_id = session('store_id');
        $customer->name = $request->name;
        $customer->gender = $request->gender;
        $customer->phone = $hp;
        $customer->birth = $request->birth;
        $customer->feature = $request->feature;

        $customer->save();

        return redirect()->route('customer.index');
    }

    public function show($id) {
        $customer = Customer::find($id);
        $reservations = $customer->reservations;

        return view('customer.show', ['customer' => $customer, 'reservations' => $reservations]);
    }

    public function edit($id) {
        $customer = Customer::find($id);

        return view('customer.edit', ['customer' => $customer]);
    }

    public function update(Request $request, $id) {
        $customer = Customer::find($id);

        //핸드폰번호 확인
        $hp = preg_replace("/[^0-9]/", "", $request->phone);
        if(!(preg_match("/^01[0-9]{8,9}$/", $hp))) {
            echo "<script>alert('핸드폰 번호를 형식에 맞게 입력해주세요.');
                    history.back();</script>";
            return;
        }

        $customer->name = $request->name;
        $customer->gender = $request->gender;
        $customer->phone = $hp;
        $customer->birth = $request->birth;
        $customer->feature = $request->feature;

        $customer->save();

        return redirect()->route('customer.index');
    }
}
