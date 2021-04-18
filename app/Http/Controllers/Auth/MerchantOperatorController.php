<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\Response;
use App\Http\Controllers\Service\EmailVerification as ServiceEmailVerification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\MerchantOperator;

class MerchantOperatorController extends Controller
{
  public function login(Request $r) {
		$auth_data = $r->only('email', 'password');
		$user = MerchantOperator::where('email', $r->email)->first();

		if (!$user)
			return Response::error("Unauthorized", 401);

			$payload = ['guard' => 'MERCHANT_OPERATOR'];

		if (!$token = auth('merchant_operator')->claims($payload)->attempt($auth_data))
			return Response::error("Unauthorized", 401);
		
		return Response::successWithData("Login Successfully", [
			'user' => $user,
			'token' => $token,
			'expires_in' => Auth::factory()->getTTL() * 60
		]);
	}

	public function forgot_password(Request $r) {
		$validated = Validator::make($r->all(), [
			'email' => 'required|string|email'
		]);

		if ($validated->fails())
			return Response::errorWithData("Forgot Password Failed",
				$validated->errors(),
				422
			);

		$user = MerchantOperator::where('email', $r->email)->first();
		if (!$user)
			return Response::error("Email is not registered", 404);

		$email_verified = ServiceEmailVerification::send_email($user->email, 'MO');
		
		return Response::successWithData(
			"Forgot Password Successfully",
			[ 'registered_email' => $email_verified ]
		);
	}

	public function verify_otp(Request $r) {
		$validated = Validator::make($r->all(), [
			'email' => 'required|string|email',
			'otp' => 'required|string'
		]);

		if ($validated->fails())
			return Response::errorWithData("Verify OTP Failed",
				$validated->errors(),
				422
			);

		return ServiceEmailVerification::verification_otp($r->email, $r->otp, 'MO');
	}

	public function reset_password(Request $r) {
		$validated = Validator::make($r->all(), [
			'email' => 'required|string|email',
			'otp' => 'required|string',
			'new_password' => 'required|string|confirmed'
		]);

		if ($validated->fails())
			return Response::errorWithData("Verify OTP Failed",
				$validated->errors(),
				422
			);

		return ServiceEmailVerification::verification_otp(
			$r->email, $r->otp, 'MO', true, $r->new_password
		);
	}

	public function reset_default_password(Request $r) {
		$validated = Validator::make($r->all(), [
			'old_password' => 'required|alpha_num',
			'new_password' => 'required|alpha_num|confirmed',
		]);

		if ($validated->fails())
			return Response::errorWithData("Reset Password Failed",
				$validated->errors(),
				422
			);

		$user = Auth::user();
		if (!Hash::check($r->old_password, $user->password))
			return Response::error("Old password did not match", 422);

		$data = MerchantOperator::find($user->id);
		$data->password = bcrypt($r->new_password);
		$data->default_password = "0";
		$data->save();

		return Response::success("Reset Password Successfully");
	}

	public function check() {
		return Response::successWithData('Verified Account', Auth::user());
	}
}
