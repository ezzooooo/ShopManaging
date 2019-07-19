<?php

namespace App\Http\Controllers;

use Auth;
use App\Reservation;
use App\Sales;
use App\Notice;
use App\Product;
use App\Policies\ReservationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\User;
use App\Customer;
use App\StoreSetting;


class ReservationController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        if(Auth::check()) {

        $items = Reservation::where('store_id', session('store_id'))->orderByDesc('created_at')->paginate(10);

        return view('reservation.index', ['items' => $items, 'stat' => "전체", 'name' => ""]);
        }else {
            return redirect()->route('login');
        }
    }

    public function create(Request $request)
    {
        if(Auth::check()) {
            $store_setting = StoreSetting::where('store_id', session('store_id'))->first();

            if($store_setting == null) {
                return redirect()->route('storesetting.create');
            }    
        }
        
        if($request->has('times')) {
            $times = $request->get('times');
            $cmp_times = explode(',', $times);
            $start_time = $cmp_times[0];
            $end_time = $cmp_times[0];
            foreach($cmp_times as $key => $value) {
                if($start_time > $value) {
                    $start_time = $value;
                }
                if($end_time < $value) {
                    $end_time = $value;
                }
            }
            $end_time++;

            $start_time = $store_setting->start_time+floor($start_time/2) . ":" . ($start_time%2 == 1 ? "30" : "00");
            $end_time = $store_setting->start_time+floor($end_time/2) . ":" . ($end_time%2 == 1 ? "30" : "00");

            $products = Product::where('store_id', session('store_id'))->get();
            $store_name = User::where('store_id', session('store_id'))->first();
            
            return view('reservation.create', ['store_name' => $store_name['name'], 'store_id' => session('store_id'),
                                            'start_time' => $start_time, 'end_time' => $end_time, 'products' => $products,
                                            'store_setting' => $store_setting]);
        }
        else if($request->has('store_id')) {
            $store_name = User::where('id', $request->get('store_id'))->first();
            $notices = Notice::where('store_id', $request->get('store_id'))->get();
            $products = Product::where('store_id', $request->get('store_id'))->get();
            $store_setting = StoreSetting::where('store_id', $request->get('store_id'))->first();
            return view('reservation.create', ['store_name' => $store_name['name'], 'store_id' => $request->get('store_id'), 
                                                'start_time' => $store_setting->start_time, 'end_time' => $store_setting->end_time, 'notices' => $notices, 'products' => $products,
                                                'store_setting' => $store_setting]);
        } else if(session()->has('store_id')){
            $store_name = User::where('store_id', session('store_id'))->first();
            $products = Product::where('store_id', session('store_id'))->get();
            $store_setting = StoreSetting::where('store_id', session('store_id'))->first();
            return view('reservation.create', ['store_name' => $store_name['name'], 'store_id' => session('store_id'), 
                                                'start_time' => $store_setting->start_time, 'end_time' => $store_setting->end_time, 'products' => $products,
                                                'store_setting' => $store_setting]);
        }
    }

    public function store(Request $request)
    {
        if(Auth::check()) {                 //관리자일때

            //유효성 검사
            $request->validate([
                'store_id' => 'integer|exists:users,id',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time' => 'required',
                'name' => 'required|string|max:191',
                'gender' => 'required|string',
                'phone' => 'required|string|max:20',
                'product' => 'required|string',
                'purpose' => 'required|string|max:191',
                'is_revisit' => 'required|string',
                'feature' => 'nullable|string',
                'birth' => 'nullable|date',
                'price' => 'required|integer',
                'memo' => 'required|string',
            ]);

            //핸드폰번호 확인
            $hp = preg_replace("/[^0-9]/", "", $request->phone);
            if(!(preg_match("/^01[0-9]{8,9}$/", $hp))) {
                echo "<script>alert('핸드폰 번호를 형식에 맞게 입력해주세요.');
                        history.back();</script>";
                return;
            }

            /*
            //성별 예외처리
            if(!($request->gender == "남자" || $request->gender == "여자")) {
                echo "<script>alert('성별은 남자 혹은 여자만 선택 가능합니다.');
                        history.back();</script>";
                return;
            }*/

            if ($request->is_revisit == "첫방문") {
                $request->validate([
                    'feature' => 'required|string',
                ]);
    
                $customer = Customer::where('store_id', $request->store_id)->where('phone', $hp)->first();

                if($customer != null && $customer->visit_count > 0) {
                    echo "<script>alert('이미 방문한 기록이 있습니다.');
                    history.back();</script>";
                    return;
                }
            } else {
                $customer = Customer::where('store_id', $request->store_id)->where('phone', $hp)->first();

                if($customer == null || $customer->visit_count == 0) {
                    echo "<script>alert('방문한 기록이 없습니다.');
                        history.back();</script>";
                        return;
                }
            }

            //예약 종료시간이 시작시간보다 한시간 이상 늦은지 확인
            $rs = strtotime($request->start_time);
            $re = strtotime($request->end_time);
            
            $r = $re - $rs;

            if(ceil($r / (60)) < 60) {
                echo "<script>alert('예약은 최소 1시간 이상만 가능합니다.');
                        history.back();</script>";
                return;
            }

            //이미 예약된 시간인지 확인
            $check = Reservation::where('store_id',session('store_id'))->where('date', $request->date)->whereNotIn('stat', ["예약확인 대기"])->get();

            foreach($check as $item) {
                $is = strtotime($item->start_time);
                $ie = strtotime($item->end_time);
                if( ($is<=$rs && $rs <= $ie) || ($is<=$re && $re<=$ie) 
                    || ($rs<=$is && $is<= $re) || ($rs<=$ie && $ie<=$re)) {
                    abort(403, '이미 예약된 시간입니다.');
                    return view('reserve.add');
                }
            }

            //이름이 한글인지 확인
            if(preg_match("/[xA1-xFE][xA1-xFE]/", $request->name)) {
                echo "<script>alert('이름은 한글만 입력 가능합니다.');
                        history.back();</script>";
                return;
            }

            //request 객체의 id와 로그인된 유저의 id 확인
            if($request->store_id != session('store_id')) {
                echo "<script>alert('자신의 매장에만 예약추가가 가능합니다.');
                    history.back();</script>";
                return;
            }

            //예약객체, 매출객체 생성
            $reservation = new Reservation;
            $sale = new Sales;
            $customer = Customer::firstOrCreate(['store_id' => $request->store_id, 'phone' => $hp], 
            ['name' => $request->name, 'gender' => $request->gender, 'birth' => $request->birth, 'feature' => $request->feature]);

            //입력 값 대입
            $reservation->store_id = $request->store_id;
            $reservation->customer_id = $customer->id;
            $reservation->date = $request->date;
            $reservation->start_time = $request->start_time;
            $reservation->end_time = $request->end_time;
            $reservation->product = $request->product;
            $reservation->purpose = $request->purpose;
            $reservation->memo = $request->memo;
            $reservation->price = $request->price;

            //매출값 대입
            $sale->store_id = $request->store_id;
            $sale->reservation_id = 0;
            $sale->customer_name = $request->name;
            $sale->product = $request->product;
            $sale->pre_cash = 0;
            $sale->cash = 0;
            $sale->card = 0;
            $sale->sales = 0;
            $sale->sales_stat = "예상매출";
            
            //트랜잭션 시작
            DB::transaction(function () use ($request, $sale, $reservation) {

            //매출객체 DB에 저장
            $sale->save();
            //예약객체 DB에 저장
            $reservation->sales_id = $sale->id; // 매출 저장 후 id값 받아오기
            $reservation->save();
            $sale->reservation_id = $reservation->id;
            $sale->save();
            });
            $customer->visit_count++;
            $customer->save();

            //예약된 날짜의 타임 테이블로 이동
            return redirect()->route('timetable.show', ['date'=>str_replace("-","",$reservation->date)]);
        } 

        else {// 게스트일때

            //유효성 검사
            $request->validate([
                'store_id' => 'integer|exists:users,id',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time' => 'required',
                'name' => 'required|string|max:191',
                'gender' => 'required|string',
                'phone' => 'required|string|max:20',
                'product' => 'required|string',
                'purpose' => 'required|string|max:191',
                'is_revisit' => 'required|string',
                'feature' => 'nullable|string',
                'birth' => 'nullable|date',
                'memo' => 'required|string',
            ]);

            //핸드폰번호 확인
            $hp = preg_replace("/[^0-9]/", "", $request->phone);
            if(!(preg_match("/^01[0-9]{8,9}$/", $hp))) {
                echo "<script>alert('핸드폰 번호를 형식에 맞게 입력해주세요.');
                        history.back();</script>";
                return;
            }

            //성별 예외처리
            if(!($request->gender == "남자" || $request->gender == "여자")) {
                echo "<script>alert('성별은 남자 혹은 여자만 선택 가능합니다.');
                        history.back();</script>";
                return;
            }
            

            //재방문 예외처리
            if ($request->is_revisit == "첫방문") {
                $request->validate([
                    'feature' => 'required|string',
                ]);
    
                $customer = Customer::where('store_id', $request->store_id)->where('phone', $hp)->first();

                if($customer != null && $customer->visit_count > 0) {
                    echo "<script>alert('이미 방문한 기록이 있습니다.');
                    history.back();</script>";
                    return;
                }
            } else {
                $customer = Customer::where('store_id', $request->store_id)->where('phone', $hp)->first();

                if($customer == null || $customer->visit_count == 0) {
                    echo "<script>alert('방문한 기록이 없습니다.');
                        history.back();</script>";
                        return;
                }
            }

            //예약 종료시간이 시작시간보다 한시간 이상 늦은지 확인
            $rs = strtotime($request->start_time);
            $re = strtotime($request->end_time);
            
            $r = $re - $rs;

            if(ceil($r / (60)) < 60) {
                echo "<script>alert('예약은 최소 1시간 이상만 가능합니다.');
                        history.back();</script>";
                return;
            }

            //이름이 한글인지 확인
            if(preg_match("/[xA1-xFE][xA1-xFE]/", $request->name)) {
                echo "<script>alert('이름은 한글만 입력 가능합니다.');
                        history.back();</script>";
                return;
            }

            //예약객체, 매출객체 생성
            $reservation = new Reservation;
            $customer = Customer::firstOrCreate(['store_id' => $request->store_id, 'phone' => $hp], 
            ['name' => $request->name, 'gender' => $request->gender, 'birth' => $request->birth, 'feature' => $request->feature]);

            //입력 값 대입
            $reservation->store_id = $request->store_id;
            $reservation->customer_id = $customer->id;
            $reservation->date = $request->date;
            $reservation->start_time = $request->start_time;
            $reservation->end_time = $request->end_time;
            $reservation->product = $request->product;
            $reservation->purpose = $request->purpose;
            $reservation->memo = $request->memo;
            $reservation->price = $request->price;
            $reservation->stat = "예약확인 대기";
 
            $reservation->save();
            $customer->save();
            
            //메인화면으로 이동
            return redirect()->route('reservations.success');
        }
    }

    public function show($id)
    {
        if(Auth::check()) {

        $item = Reservation::find($id);
        $re_items = Reservation::whereNotIn('id', [$id])->where('store_id', session('store_id'))->where('customer_id', $item->customer->id)->orderByDesc('date')->get();

        if( ($item->store_id !== session('store_id')) ) {
            abort(403, "권한이 없습니다.");
        }

        return view('reservation.show', ['item' => $item, 're_items' => $re_items]);
        
        }else {
            return redirect()->route('login');
        }
    }

    public function edit($id)
    {
        if(Auth::check()) {

        $item = Reservation::find($id);
        
        if( ($item->store_id !== session('store_id')) ) {
            abort(403, "권한이 없습니다.");
        }

        $store = User::where('store_id', session('store_id'))->first();
        $products = Product::where('store_id', session('store_id'))->get();
        $store_setting = StoreSetting::where('store_id', session('store_id'))->first();

        return view('reservation.edit', ['item'=>$item, 'store_name' => $store['name'], 
                                        'store_id' => session('store_id'), 'products' => $products,
                                        'store_setting' => $store_setting]);
        }else {
            return redirect()->route('login');
        }
    }

    public function update(Request $request, $id)
    {
        if(Auth::check()) {                 //관리자일때

            //유효성 검사
            $request->validate([
                'store_id' => 'integer|exists:users,id',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time' => 'required',
                'name' => 'required|string|max:191',
                'gender' => 'required|string',
                'phone' => 'required|string|max:20',
                'product' => 'required|string',
                'purpose' => 'required|string|max:191',
                'is_revisit' => 'required|string',
                'feature' => 'nullable|string',
                'birth' => 'nullable|date',
                'memo' => 'required|string',
            ]);

            $reservation = Reservation::find($id);

            //핸드폰번호 확인
            $hp = preg_replace("/[^0-9]/", "", $request->phone);
            if(!(preg_match("/^01[0-9]{8,9}$/", $hp))) {
                echo "<script>alert('핸드폰 번호를 형식에 맞게 입력해주세요.');
                        history.back();</script>";
                return;
            }

            /*
            //성별 예외처리
            if(!($request->gender == "남자" || $request->gender == "여자")) {
                echo "<script>alert('성별은 남자 혹은 여자만 선택 가능합니다.');
                        history.back();</script>";
                return;
            }*/

            if ($request->is_revisit == "첫방문") {
                $request->validate([
                    'feature' => 'required|string',
                ]);
    
                $customer = Customer::where('store_id', $request->store_id)->where('phone', $hp)->first();

                if($customer != null && $customer->visit_count > 1) {
                    echo "<script>alert('이미 방문한 기록이 있습니다.');
                    history.back();</script>";
                    return;
                }
            } else {
                $customer = Customer::where('store_id', $request->store_id)->where('phone', $hp)->first();

                if($customer == null || $customer->visit_count <= 1) {
                    echo "<script>alert('방문한 기록이 없습니다.');
                        history.back();</script>";
                        return;
                }
            }

            //예약 종료시간이 시작시간보다 한시간 이상 늦은지 확인
            $rs = strtotime($request->start_time);
            $re = strtotime($request->end_time);
            
            $r = $re - $rs;

            if(ceil($r / (60)) < 60) {
                echo "<script>alert('예약은 최소 1시간 이상만 가능합니다.');
                        history.back();</script>";
                return;
            }

            //이미 예약된 시간인지 확인
            $check = Reservation::where('store_id',session('store_id'))->whereNotIn('id', [$reservation->id])->where('date', $request->date)->whereNotIn('stat', ["예약확인 대기"])->get();

            foreach($check as $item) {
                $is = strtotime($item->start_time);
                $ie = strtotime($item->end_time);
                if( ($is<=$rs && $rs <= $ie) || ($is<=$re && $re<=$ie) 
                    || ($rs<=$is && $is<= $re) || ($rs<=$ie && $ie<=$re)) {
                    abort(403, '이미 예약된 시간입니다.');
                    return view('reserve.add');
                }
            }

            //이름이 한글인지 확인
            if(preg_match("/[xA1-xFE][xA1-xFE]/", $request->name)) {
                echo "<script>alert('이름은 한글만 입력 가능합니다.');
                        history.back();</script>";
                return;
            }

            //request 객체의 id와 로그인된 유저의 id 확인
            if($request->store_id != session('store_id')) {
                echo "<script>alert('자신의 매장에만 예약추가가 가능합니다.');
                    history.back();</script>";
                return;
            }

            //매출객체, 고객객체 생성
            $sale = Sales::firstOrNew(['id' => $reservation->sales_id], 
            ['store_id' => $request->store_id, 'customer_name' => $request->name, 'product' => $request->product,
            'pre_cash' => 0, 'cash' => 0, 'sales' => 0, 'sales_stat' => "예상매출"]);

            $customer = Customer::firstOrCreate(['store_id' => $request->store_id, 'phone' => $hp], 
            ['name' => $request->name, 'gender' => $request->gender, 'birth' => $request->birth, 'feature' => $request->feature]);

            //매출변경
            //$customer->sales = $customer->sales - $reservation->price + $request->price;
            //$sale->sales = $request->price;

            //입력 값 대입
            $reservation->store_id = $request->store_id;
            $reservation->customer_id = $customer->id;
            $reservation->date = $request->date;
            $reservation->start_time = $request->start_time;
            $reservation->end_time = $request->end_time;
            $reservation->product = $request->product;
            $reservation->purpose = $request->purpose;
            $reservation->memo = $request->memo;
            $reservation->price = $request->price;
            if($reservation->stat == "예약확인 대기")
                $reservation->stat = "예약확정 대기";

            //트랜잭션 시작
            DB::transaction(function () use ($request, $sale, $reservation) {

            //매출객체 DB에 저장
            $sale->save();
            //예약객체 DB에 저장
            $reservation->sales_id = $sale->id; // 매출 저장 후 id값 받아오기
            $reservation->save();
            });
            $customer->save();

            //예약된 날짜의 타임 테이블로 이동
            return redirect()->route('timetable.show', ['date'=>str_replace("-","",$reservation->date)]);
    }
        else {
            return redirect()->route('login');
        }
    }

    public function destroy($id)
    {
        if(Auth::check()) {

        $reservation = Reservation::find($id);
        $sale = Sales::find($reservation->sales_id);
        $customer = $reservation->customer;

        if( ($reservation->store_id !== session('store_id')) ) {
            abort(403, "권한이 없습니다.");
        }

        $customer->visit_count--;
        if($reservation->stat == "예약확정" || $reservation->stat == "시술완료") {
            $customer->sales -= $sale->sales;
        }
        
         //트랜잭션 시작
         DB::transaction(function() use($reservation, $sale, $customer) {
        $reservation->delete();
        $sale->delete();
        $customer->save();
        });

        return redirect()->route('timetable.index');
        }else {
            return redirect()->route('login');
        }
    }

    public function confirm(Request $request, $id)
    {
        if(Auth::check()) {

        $reservation = Reservation::find($id);
        $sale = $reservation->sales;
        $customer = $reservation->customer;

        if( ($reservation->store_id !== session('store_id')) ) {
            abort(403, "권한이 없습니다.");
        }

        $reservation->stat = "예약확정";
        $sale->sales_stat = "확정매출";
        $sale->pre_cash = $request->get('price');
        $sale->cash = $request->get('price');
        $sale->sales += $request->get('price');
        $customer->sales += $request->get('price');

        //트랜잭션 시작
        DB::transaction(function () use ($reservation, $sale, $customer) {
        $reservation->save();
        $sale->save();
        $customer->save();
        });
        
        return redirect()->route('reservations.show', ['item' => $reservation]);
        } else {
            return redirect()->route('login');
        }
    }

    public function search(Request $request) {

        if(Auth::check()) {
            $name = $request->get('reservation_name');
            $stat = $request->get('reservation_stat');

            if($stat == "전체") {
                $items = Reservation::where('memo', 'LIKE', "%$name%")->where('store_id', session('store_id'))->paginate(20);    
            } else {
                $items = Reservation::where('memo', 'LIKE', "%$name%")->where('store_id', session('store_id'))->where('stat', $stat)->paginate(20);
            }

            return view('reservation.index', ['items' => $items, 'stat' => $stat, 'name' => $name]);
        }else {
            return redirect()->route('login');
        }
    }

    public function excel_down() {
        $reservations = Reservation::where('store_id', session('store_id'))->orderByDesc('created_at')->get();
        
        Excel::create(date('Y-m-d'. ' 예약현황'), function ($excel) use($reservations)
        {
            // Set the title
            $excel->setTitle('예약리스트');
        
            // Chain the setters
            $excel->setCreator(session('user_name'))
                ->setCompany(session('user_name'));
        
            // Call them separately
            $excel->setDescription('전체 예약 리스트를 보여주는 엑셀');
        
            // header
            $excel->sheet('예약리스트', function($sheet) use($reservations) {
                // Set auto size for sheet
                $sheet->setAutoSize(true);

                // Manipulate first row
                $num = 1;
        
                $sheet->row($num++, [
                    '예약번호', '예약날짜', '예약 시작시간', '예약 종료시간', '예약자명', '성별', 
                    '생년월일', '전화번호', '예약종류', '예약목적', '방문횟수', '특이사항', 
                    '메모', '예약상태', '결제방법', '예약금', '최초예약일', '예약수정일',
                ]);
        
                // Set cyan background
                $sheet->row(1, function($row) {
                    $row->setBackground('#00FFFF');
                });
        
                foreach ($reservations as $rs) {
                    $sheet->row($num++, [
                        $rs['id'], $rs['date'], $rs['start_time'], $rs['end_time'], $rs->customer['name'], $rs->customer['gender'], 
                        $rs->customer['birth'], $rs->customer['phone'], $rs['product'], $rs['purpose'], $rs->customer['visit_count'], $rs->customer['feature'],
                        $rs['memo'], $rs['stat'], $rs['sales_type'], $rs['price'], $rs['created_at'], $rs['updated_at'],
                    ]);
                }

            });
        
        })->download('xlsx');
    }
    
    public function add(Request $request, $id) {
        if(Auth::check()) {                 //관리자일때

            $reservation = Reservation::find($id);

            //이미 예약된 시간인지 확인
            $check = Reservation::where('store_id',session('store_id'))->whereNotIn('id', [$id])->where('date', $reservation->date)->whereNotIn('stat', ["예약확인 대기"])->get();

            $rs = strtotime($reservation->start_time);
            $re = strtotime($reservation->end_time);

            foreach($check as $item) {
                $is = strtotime($item->start_time);
                $ie = strtotime($item->end_time);
                if( ($is<=$rs && $rs <= $ie) || ($is<=$re && $re<=$ie) 
                    || ($rs<=$is && $is<= $re) || ($rs<=$ie && $ie<=$re)) {
                    abort(403, '이미 예약된 시간입니다.');
                }
            }

            //매출객체 생성
            $sale = new Sales;
            $customer = Customer::find($reservation->customer->id);

            //예약상태 변경
            $reservation->stat = "예약확정 대기";

            //매출값 대입
            $sale->store_id = $reservation->store_id;
            $sale->reservation_id = 0;
            $sale->customer_name = $reservation->customer->name;
            $sale->product = $reservation->product;
            $sale->pre_cash = 0;
            $sale->cash = 0;
            $sale->card = 0;
            $sale->sales = 0;
            $sale->sales_stat = "예상매출";
            
            //트랜잭션 시작
            DB::transaction(function () use ($request, $sale, $reservation) {

            //매출객체 DB에 저장
            $sale->save();
            //예약객체 DB에 저장
            $reservation->sales_id = $sale->id; // 매출 저장 후 id값 받아오기
            $reservation->save();
            $sale->reservation_id = $reservation->id;
            $sale->save();
            });
            $customer->visit_count++;
            $customer->save();

            //예약된 날짜의 타임 테이블로 이동
            return redirect()->route('timetable.show', ['date'=>str_replace("-","",$reservation->date)]);
        } else {
            return redirect()->route('login');
        }
    }

    public function complete($id) {
        $reservation = Reservation::find($id);
        $sales = $reservation->sales;

        return view('sales.edit', ['sales' => $sales]);
    }
}

