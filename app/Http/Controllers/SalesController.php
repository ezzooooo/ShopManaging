<?php

namespace App\Http\Controllers;

use Auth;
use App\Sales;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Maatwebsite\Excel\Facades\Excel;
class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

            if ($request->has(['sales_type'])) {
                if($request->sales_type == "전체") {
                    $sales = Sales::where('store_id', session('store_id'))->whereBetween('updated_at', [$start_date, $end_date])
                    ->orderByDesc('updated_at')->get();    
                } else {
                    $sales = Sales::where('store_id', session('store_id'))->where('sales_type', $request->sales_type)
                    ->orderByDesc('updated_at')->whereBetween('updated_at', [$start_date, $end_date])->get();
                }
            } else {
                $request->sales_type = "전체";
                $sales = Sales::where('store_id', session('store_id'))->whereBetween('updated_at', [$start_date, $end_date])
                ->orderByDesc('updated_at')->get();    
            }

            $out_start_date = date("Y-m-d", strtotime($start_date));
            $out_end_date = date("Y-m-d", strtotime($end_date));

            $estimate_sales = 0;
            $definite_sales = 0;

            foreach ($sales as $sale) {
                if($sale->sales_stat == "예상매출") {
                    $estimate_sales += $sale->sales;
                } elseif ($sale->sales_stat == "확정매출") {
                    $definite_sales += $sale->sales;
                } else {
                    return;
                }
            }

            return view('sales.index', ['sales' => $sales, 'start_date' =>$out_start_date, 'end_date' => $out_end_date, 
            'estimate_sales' => $estimate_sales, 'definite_sales' => $definite_sales, 'sales_type' => $request->sales_type,
            'day_before' => $day_before, 'week_before' => $week_before, 'half_month_before' => $half_month_before, 
            'month_before' => $month_before, 'three_month_before' => $three_month_before]);
        } else {
            return redirect()->route('login');
        }
    }

    public function create() {
        return view('sales.create');
    }
    
    public function store(Request $request) {
        $sales = new Sales;

        $sales->store_id = session('store_id');
        $sales->customer_name = $request->customer_name;
        $sales->customer_kind = $request->customer_kind;
        $sales->sales = $request->sale;
        $sales->sales_type = $request->sales_type;
        $sales->sales_stat = "확정매출";
        $sales->created_at = $request->date;
        $sales->updated_at = $request->date;

        $sales->save();

        return redirect()->route('sales.index');
    }

    public function update(Request $request, $id) {
        $sales = Sales::find($id);
        $reservation = $sales->reservations;

        $sales->cash += $request->cash;
        $sales->card += $request->card;
        $sales->sales += ($request->cash + $request->card);

        $reservation->stat = "시술완료";

        $sales->save();
        $reservation->save();

        return redirect()->route('sales.index');
    }

    public function excel_down(Request $request) {

        if ($request->has(['excel_start_date', 'excel_end_date'])) {
            $start_date = date("Y-m-d 00:00:00", strtotime($request->get('excel_start_date')));
            $end_date = date("Y-m-d 23:59:59", strtotime($request->get('excel_end_date')));
        }

        if ($request->has('excel_sales_type')) {
            if($request->excel_sales_type == "전체") {
                $sales = Sales::where('store_id', session('store_id'))->whereBetween('updated_at', [$start_date, $end_date])->
                orderByDesc('updated_at')->get();    
            } else {
                $sales = Sales::where('store_id', session('store_id'))->where('sales_type', $request->excel_sales_type)->
                whereBetween('updated_at', [$start_date, $end_date])->orderByDesc('updated_at')->get();
            }
        } else {
            $sales = Sales::where('store_id', session('store_id'))->whereBetween('updated_at', [$start_date, $end_date])->
            orderByDesc('updated_at')->get();    
        }
        
        Excel::create(date('Y-m-d'. ' 매출현황'), function ($excel) use($sales)
        {
            // Set the title
            $excel->setTitle('매출현황');
        
            // Chain the setters
            $excel->setCreator(session('user_name'))
                ->setCompany(session('user_name'));
        
            // Call them separately
            $excel->setDescription('매출 리스트를 보여주는 엑셀파일');
        
            // header
            $excel->sheet('예약리스트', function($sheet) use($sales) {
                // Set auto size for sheet
                $sheet->setAutoSize(true);

                // Manipulate first row
                $num = 1;
        
                $sheet->row($num++, [
                    '일자', '고객이름', '상품', '현금', '카드', '총매출', '상태',
                ]);
        
                // Set cyan background
                $sheet->row(1, function($row) {
                    $row->setBackground('#00FFFF');
                });
        
                foreach ($sales as $sale) {
                    $sheet->row($num++, [
                        $sale['updated_at'], $sale['customer_name'], $sale['product'], 
                        $sale['cash'], $sale['card'], $sale['sales'], $sale['sales_stat'], 
                    ]);
                }

            });
        
        })->download('xlsx');
    }


}
