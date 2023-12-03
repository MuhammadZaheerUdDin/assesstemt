<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Order;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MerchantController extends Controller
{
    protected $merchantService;
    public function __construct(
        MerchantService $merchantService
    ) {
        $this->merchantService=$merchantService;
    }

    /**
     * Useful order statistics for the merchant API.
     *
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form* {count: total number of orders in range,
     * commission_owed: amount of unpaid commissions for orders with an affiliate,
     * revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {
        $from_date = $request['from'];
        $to_date = $request['to'];
        $user = auth()->user();
        $orders = Order::query()->where('merchant_id', $user->merchant->id)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->get();
        //commisionOwed could be done using filtering above Order query but it will loop that time.
        // as we would have million of records ,so we use new query
        $commissionsOwed =  Order::query()->where('merchant_id', $user->merchant->id)
            ->whereNotNull('affiliate_id') // Filter for records where affiliate_id is not null
            ->whereBetween('created_at', [$from_date, $to_date])
            ->sum('commission_owed');
        $count = $orders->count();
        $revenue = $orders->sum('subtotal');
        $response = [
            'count' => $count,
            'revenue' => $revenue,
            'commissions_owed' => $commissionsOwed,
        ];

        return response()->json($response);
    }
}
