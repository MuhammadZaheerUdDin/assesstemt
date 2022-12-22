<?php

namespace App\Services;

use App\Exceptions\AffiliateCreateException;
use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AffiliateService
{
    public function __construct(
        protected ApiService $apiService
    ) {}

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param  Merchant $merchant
     * @param  string $email
     * @param  string $name
     * @param  float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate
    {
        // TODO: Complete this method
//                dd($merchant,$email,$commissionRate);
        $user=new User();
        $user->name=$name;
        $user->email=$email;
        $user->password=encrypt('123456');
        $user->type='merchant';
        $user->save();

        $Merchant1=new Merchant();
        $Merchant1->user_id=$user->id;
        $Merchant1->domain="data";
        $Merchant1->display_name="name";
        $Merchant1->save();


        $Affiliate = new Affiliate();
        $Affiliate->merchant_id = $Merchant1->id;
        $Affiliate->user_id = $user->id;
        $Affiliate->commission_rate = $commissionRate;
        $Affiliate->discount_code = $this->apiService->createDiscountCode($merchant)['code'];
        $Affiliate->save();
        return $Affiliate;

    }
}
