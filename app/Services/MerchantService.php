<?php

namespace App\Services;

use App\Jobs\PayoutOrderJob;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use function Symfony\Component\VarDumper\Cloner\__isset;

class MerchantService
{
    /**
     * Register a new user and associated merchant.
     * Hint: Use the password field to store the API key.
     * Hint: Be sure to set the correct user type according to the constants in the User model.
     *
     * @param array{domain: string, name: string, email: string, api_key: string} $data
     * @return Merchant
     */
    public function register(array $data): Merchant
    {
//        dd($data);
        // TODO: Complete this method
        $user=new User();
        $user->name=$data['name'];
        $user->email=$data['email'];
        $user->password=$data['api_key'];
        $user->type='merchant';
        $user->save();

        $Merchant=new Merchant();
        $Merchant->user_id=$user->id;
        $Merchant->domain=$data['domain'];
        $Merchant->display_name=$data['name'];
        $Merchant->save();
        return $Merchant;
//        return json_encode($Merchant);
    }

    /**
     * Update the user
     *
     * @param array{domain: string, name: string, email: string, api_key: string} $data
     * @return void
     */
    public function updateMerchant(User $user, array $data)
    {
        // TODO: Complete this method
        $data['api_key']=encrypt($data['api_key']);
        $user->update($data);
        $user->merchant()->update(['domain'=>$data['domain'],'display_name'=>$data['name']]);
    }

    /**
     * Find a merchant by their email.
     * Hint: You'll need to look up the user first.
     *
     * @param string $email
     * @return Merchant|null
     */
    public function findMerchantByEmail(string $email): ?Merchant
    {
        // TODO: Complete this method
            $user=User::where('email',$email)->first();
            if(isset($user->id)){
                $Merchant=Merchant::where('user_id',$user->id)->first();
            }
                if(isset($Merchant->id)){
                    return $Merchant;
                }
                else{
                    return null;
                }

    }

    /**
     * Pay out all of an affiliate's orders.
     * Hint: You'll need to dispatch the job for each unpaid order.
     *
     * @param Affiliate $affiliate
     * @return void
     */
    public function payout(Affiliate $affiliate)
    {
        // TODO: Complete this method
        Order::where('affiliate_id',$affiliate->id)->update(['payout_status'=>'paid']);
    }
}
