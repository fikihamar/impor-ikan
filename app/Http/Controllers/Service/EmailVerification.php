<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Helper\EmailVerification as HelperEmailVerification;
use App\Http\Controllers\Helper\Response;
use App\Mail\EmailVerification as MailEmailVerification;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Models\ClientVerification;
use App\Models\MerchantOperator;
use App\Models\MerchantOperatorVerification;
use App\Models\Operator;
use App\Models\OperatorVerification;
use App\Models\Client;

use stdClass;

class EmailVerification {
  public static function send_email($email, $role) {
		$otp = HelperEmailVerification::generate_otp();
		$d=Carbon::now();
		$by_email = ['email' => $email];
		$update_data = ['email' => $email,'created_at'=>$d, 'otp' => $otp ];

		$data = new stdClass();

		if ($role === 'O') {
			OperatorVerification::updateOrInsert(
				$by_email, $update_data
			);

			$data->role = 'Operator';
			$data->otp = $otp;
			
		} else if ($role === 'MO') {
			MerchantOperatorVerification::updateOrInsert(
				$by_email, $update_data
			);

			$data->role = 'Merchant Operator';
			$data->otp = $otp;
		} else {
			ClientVerification::updateOrInsert(
				$by_email,$update_data 
			);

			$data->role = 'Customer';
			$data->otp = $otp;
		}

		Mail::to($email)->send(new MailEmailVerification($data));

		return $email;
	}

	public static function verification_otp($email ,$otp, $role, $reset = false, $new_password = "") {
		$filter_data = [['email', $email], ['otp', $otp]];

		if ($role === 'O') {
			$data = OperatorVerification::where($filter_data)->first();
		} else if ($role === 'MO') {
			$data = MerchantOperatorVerification::where($filter_data)->first();
		} else {
			$data = ClientVerification::where($filter_data)->first();
		}

		if (!$data)
			return Response::error("Verification Failed");

		if (!$reset) {
			$time_created = new Carbon($data->created_at);
			$exp_time = $time_created->addMinute(5);

			$time_now = Carbon::now();

			$diff = $time_now->diffInMinutes($exp_time, false);
			
			if ($diff <= 0)
				return Response::error('OTP Expired');
			
			$update=Client::where('email',$email)->first();
			$update->email_verified_at = Carbon::now()->toDateTimeString();
			$update->save();
			
// 			$data->delete();
	
			return Response::success("Verification Successfully");
		}

		if ($role === 'O') {
			$user_data = Operator::where('email', $email)->first();
		} else if ($role === 'MO') {
			$user_data = MerchantOperator::where('email', $email)->first();
		} else {
			$user_data = Client::where('email', $email)->first();
		}

		$user_data->password = bcrypt($new_password);
		$user_data->save();
		
		$data->delete();

		return Response::success("Reset Password Successfully");
	}
}