<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\StoreSetting;


class UserController extends Controller
{
    public function index() {
        if(Auth::check()) {
            $store = User::where('store_id', session('store_id'))->first();
            $user = User::find(session('user_id'));
            $hostname=$_SERVER["HTTP_HOST"];
            $url = $hostname . "/reservations/create?store_id=" . $store['id'];
            $workers = User::where('store_id', session('store_id'))->where('role', 2)->get();;
            $store_setting = StoreSetting::where('store_id', session('store_id'))->first();

            return view('user.index', ['store' => $store, 'user' => $user, 'url' => $url, 'workers' => $workers, 
                                        'store_setting' => $store_setting]);
        } else {
            return redirect()->route('login');
        }
    }

    public function create() {
        return view('user.create');
    }

    public function store(Request $request) {
        $worker = new User;

        //핸드폰번호 확인
        $hp = preg_replace("/[^0-9]/", "", $request->phone);
        if(!(preg_match("/^01[0-9]{8,9}$/", $hp))) {
            echo "<script>alert('핸드폰 번호를 형식에 맞게 입력해주세요.');
                    history.back();</script>";
            return;
        }

        $worker->store_id = session('store_id');
        $worker->role = 2;
        $worker->name = $request->name;
        $worker->category = Auth::user()->category;
        $worker->phone = $hp;
        $worker->email = $request->email;
        $worker->password = bcrypt($request->password);

        $worker->save();

        return redirect()->route('user.index');
    }

    public function destroy($id) {
        $worker = User::find($id);

        $worker->delete();

        return redirect()->route('user.index');
    }

    public function changepasswdcheck(Request $request) {
        if(Auth::check()) {
            if(!(Hash::check($request->get('cur_passwd'), Auth::user()->password))) {
                return redirect()->back()->with("error", "현재 비밀번호와 다릅니다.");
            }

            if(strcmp($request->get('cur_passwd'), $request->get('new_passwd')) == 0) {
                return redirect()->back()->with("error", "현재 비밀번호와 같은 비밀번호는 사용할 수 없습니다.");
            }

            $validatedData = $request->validate([
                'cur_passwd' => 'required',
                'new_passwd' => 'required|string|min:6|confirmed',
            ]);

            $user = Auth::user();
            $user->password = bcrypt($request->get('new_passwd'));
            $user->save();

            return redirect()->back()->with("success", "비밀번호가 변경되었습니다.");
        } else {
            return redirect()->route('login');
        }
    }

    public function inquire_email(Request $request) {
        if(count(User::where('email', $request->inquire_email)->get()) == 1) {
            return redirect()->back()->with("success", "아이디가 있습니다.");
        } else {
            return redirect()->back()->with("error", "아이디가 없습니다.");
        }
    }

    
}
