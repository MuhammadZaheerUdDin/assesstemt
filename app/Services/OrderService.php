<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use function Symfony\Component\VarDumper\Cloner\__isset;
use Illuminate\Contracts\Encryption\DecryptException;


class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService ,        protected ApiService $apiService
    ) {}

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method
        //        $user=User::where('email',$data['customer_email']);
        //        'commission_owed' => $data['subtotal_price'] * $affiliate->commission_rate,

        $user1=User::where('email',$data['customer_email'])->get();
        if(!isset($user1->id)){
            $user=new User();
            $user->name=$data['customer_name'];
            $user->email=$data['customer_email'];
            $user->password=encrypt('123456');
            $user->type='merchant';
            $user->save();
            $Merchant1=new Merchant();
            $Merchant1->user_id=$user->id;
            $Merchant1->domain="data.com";
            $Merchant1->display_name=$data['customer_name'];
            $Merchant1->save();
            $Affiliate=new Affiliate();
            $Affiliate->user_id=$user->id;
            $Affiliate->merchant_id=$user->id;
            $Affiliate->commission_rate=.1;
//                    $Affiliate->discount_code=$this->apiService->createDiscountCode($Merchant1)['code'];
            $Affiliate->discount_code="b60ddf5e-c349-4a0b-898c-d7e49baffa4f";
            $Affiliate->save();

            $order=new Order();
            $order->merchant_id=$Merchant1->id;
            $order->affiliate_id=$Affiliate->id;
            $order->subtotal=$data['subtotal_price'];
            $order->commission_owed=$data['subtotal_price']*0.1;
            $order->payout_status='unpaid';
            $order->discount_code="b60ddf5e-c349-4a0b-898c-d7e49baffa4f";
            $order->save();

        }
    }
}
