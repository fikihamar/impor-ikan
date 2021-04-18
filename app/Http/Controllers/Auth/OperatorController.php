<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\Response;
use App\Http\Controllers\Service\EmailVerification as ServiceEmailVerification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Operator;

class OperatorController extends Controller
{
	public function login(Request $r) {
		$auth_data = $r->only('email', 'password');
		$user_data = Operator::where('email', $r->email)->first();

		if (!$user_data)
			return Response::error("Unauthorized", 401);

		$payload = ['guard' => 'OPERATOR'];
			
		if (!$token = auth()->claims($payload)->attempt($auth_data))
			return Response::error("Unauthorized", 401);
			
		return Response::successWithData("Login Successfully", [
			'user' => $user_data,
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

		$user = Operator::where('email', $r->email)->first();
		if (!$user)
			return Response::error("Email is not registered", 404);

		$email_verified = ServiceEmailVerification::send_email($user->email, 'O');

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

		return ServiceEmailVerification::verification_otp($r->email, $r->otp, 'O');
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
			$r->email, $r->otp, 'O', true, $r->new_password
		);
	}

	public function check() {
		return Response::successWithData('Verified Account', Auth::user());
	}
}
