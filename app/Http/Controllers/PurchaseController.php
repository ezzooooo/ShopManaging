<?php

namespace App\Http\Controllers;

use Auth;
use App\Purchase;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    public function index(Request $request) {
        if(Auth::check()) {
            $day_before = date("Y-m-d", strtotime("Now"));
            $week_before = date("Y-m-d", strtotime("-1 weeks"));
            $half_month_before = date("Y-m-d", strtotime("-15 days"));
            $month_before = date("Y-m-d", strtotime("-1 months"));
            $three_month_before = date("Y-m-d", strtotime("-3 months"));
            
            if ($request->has(['start_date', 'end_date'])) {
                $start_date = date("Y-m-d 00:00:00", strtotime($request->get('start_date')));
                $end_date = date("Y-m-d 23:59:59", strtotime($request->get('end_date')));
            } 
            else {
                $start_date = date("Y-m-d 00:00:00", strtotime("-1 months"));
                $end_date = date("Y-m-d 23:59:59", strtotime("Now"));
            }

            $purchases = Purchase::where('store_id', session('store_id'))->whereBetween('date', [$start_date, $end_date])
            ->orderByDesc('date')->get();    

            $out_start_date = date("Y-m-d", strtotime($start_date));
            $out_end_date = date("Y-m-d", strtotime($end_date));

            $purchase_price = 0;

            foreach ($purchases as $purchase) {
                $purchase_price += $purchase->price;
            }

            return view('purchase.index', ['purchases' => $purchases, 'start_date' =>$out_start_date, 'end_date' => $out_end_date, 
            'purchase_price' => $purchase_price,
            'day_before' => $day_before, 'week_before' => $week_before, 'half_month_before' => $half_month_before, 
            'month_before' => $month_before, 'three_month_before' => $three_month_before]);
        } else {
            return redirect()->route('login');
        }
    }

    public function create() {
        return view('purchase.create');
    }

    public function store(Request $request) {
        $purchase = New Purchase;

        $purchase->store_id = session('store_id');
        $purchase->date = $request->date;
        $purchase->partner = $request->partner;
        $purchase->content = $request->content;
        $purchase->quantity = $request->quantity;
        $purchase->unit_price = $request->unit_price;
        $purchase->price = $request->price;
        $purchase->memo = $request->memo;

        $purchase->save();

        return redirect()->route('purchase.index');
    }

    public function excel_down(Request $request) {

        if ($request->has(['excel_start_date', 'excel_end_date'])) {
            $start_date = date("Y-m-d 00:00:00", strtotime($request->get('excel_start_date')));
            $end_date = date("Y-m-d 23:59:59", strtotime($request->get('excel_end_date')));
        }

        $purchases = Purchase::where('store_id', session('store_id'))->whereBetween('date', [$start_date, $end_date])->
        orderByDesc('date')->get();    
        
        Excel::create(date('Y-m-d'. ' 매입현황'), function ($excel) use($purchases)
        {
            // Set the title
            $excel->setTitle('매입현황');
        
            // Chain the setters
            $excel->setCreator(session('user_name'))
                ->setCompany(session('user_name'));
        
            // Call them separately
            $excel->setDescription('매입 리스트를 보여주는 엑셀파일');
        
            // header
            $excel->sheet('매입리스트', function($sheet) use($purchases) {
                // Set auto size for sheet
                $sheet->setAutoSize(true);

                // Manipulate first row
                $num = 1;
        
                $sheet->row($num++, [
                    '일자', '매입처', '상품', '수량', '단가', '가격', '메모',
                ]);
        
                // Set cyan background
                $sheet->row(1, function($row) {
                    $row->setBackground('#00FFFF');
                });
        
                foreach ($purchases as $purchase) {
                    $sheet->row($num++, [
                        $purchase['date'], $purchase['partner'], $purchase['content'], 
                        $purchase['quantity'], $purchase['unit_price'], $purchase['price'], $purchase['memo'],
                    ]);
                }

            });
        
        })->download('xlsx');
    }
}
