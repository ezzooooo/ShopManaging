<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use App\Sales;

class PurchaseofSalesController extends Controller
{
    public function index() {

        $curYear = (int)date('Y');
        $curMonth = (int)date('m');

        $Month = array();
        $Month_sales = array();
        $Month_purchase = array();
        $Month_profit = array();

        for($i = 0; $i < 12; $i++) {
            $Month_start = date("Y-m-d 00:00:00", mktime(0, 0, 0, $curMonth-$i, 1, $curYear));
            $Month_end = date("Y-m-d 23:59:59", mktime(0, 0, 0, $curMonth+1-$i, 0, $curYear));

            $Month_sales_items = Sales::where('store_id', session('store_id'))->whereBetween('updated_at', [$Month_start, $Month_end])
                    ->orderByDesc('updated_at')->get();
            $Month_purchase_items = Purchase::where('store_id', session('store_id'))->whereBetween('date', [$Month_start, $Month_end])
            ->orderByDesc('date')->get();

            $curMonth_sales = 0;
            $curMonth_purchase = 0;

            foreach ($Month_sales_items as $value) {
                $curMonth_sales += $value->sales;
            }
            foreach ($Month_purchase_items as $value) {
                $curMonth_purchase += $value->price;
            }

            $Month[$i] = date("Y-m", mktime(0, 0, 0, $curMonth-$i, 1, $curYear));
            $Month_sales[$i] = $curMonth_sales;
            $Month_purchase[$i] = $curMonth_purchase;
            $Month_profit[$i] = $curMonth_sales - $curMonth_purchase;
        }

        $Month = json_encode($Month);
        $Month_sales = json_encode($Month_sales);
        $Month_purchase = json_encode($Month_purchase);
        $Month_profit = json_encode($Month_profit);

        return view('purchaseofsales', ['Month' => $Month, 'Month_sales' => $Month_sales, 
        'Month_purchase' => $Month_purchase, 'Month_profit' => $Month_profit]);
    }
}
