<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Services\MerchantService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MerchantController extends Controller
{
    public function __construct(
        MerchantService $merchantService,OrderService $_OrderService
    ) {}

    /**
     * Useful order statistics for the merchant API.
     *
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request)
    {
        // count: total number of orders in range
        $order=Order::whereBetween('created_at',[$request->from,$request->to]);
        $commission_owed=Order::where('payout_status','unpaid')->sum('commission_owed');
        $revenue=Order::where('payout_status','unpaid')->sum('subtotal');
        //                $commission_owed=$order->where('payout_status','unpaid')->merchants()->where('turn_customers_into_affiliates','1')->sum('commission_owed');
        return response()->json(['count' =>$order->count(),'commission_owed' =>$commission_owed ,'$revenue',$revenue]);
        // TODO: Complete this method
    }
    public function order()
    {
        // TODO: Complete this method
        $order=Order::all();
        return $order;
    }
//    public function orderStats(Request $request): JsonResponse
//    {
//        // TODO: Complete this method
//    }
}
