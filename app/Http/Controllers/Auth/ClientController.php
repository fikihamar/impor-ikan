<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\Response;
use App\Http\Controllers\Service\EmailVerification as ServiceEmailVerification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Client;
use App\Models\ClientVerification;

class ClientController extends Controller
{
	public function register(Request $r) {
		$validated = Validator::make($r->all(), [
			'name'=> 'required|string',
			'email' => 'required|string|email|max:255|unique:clients',
			'password' => 'required|string|min:6|confirmed',
			'phone_number'=> 'required|string|min:12|unique:clients',
			'address'=> 'required|string',
		]);

		if ($validated->fails())
			return Response::errorWithData("Register Failed",
				$validated->errors(),
				422
			);

		$user = Client::create([
			'name' => $r->name,
			'email' => $r->email,
			'password' => bcrypt($r->password),
			'phone_number' => $r->phone_number,
			'address' => $r->address
		]);

		$email_verified = ServiceEmailVerification::send_email($user->email, 'C');

		return Response::successWithData(
			"Register Successfully",
			[ 'registered_email' => $email_verified ]
		);
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

		$user = Client::where('email', $r->email)->first();
		if (!$user)
			return Response::error("Email is not registered", 404);

		$email_verified = ServiceEmailVerification::send_email($user->email, 'C');

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

		return ServiceEmailVerification::verification_otp($r->email, $r->otp, 'C');
	}
	public function verify_email(Request $r) {
		$validated = Validator::make($r->all(), [
			'otp' => 'required|string'
		]);

		if ($validated->fails())
			return Response::errorWithData("Verify OTP Failed",
				$validated->errors(),
				422
			);
		$email=Auth::user()->email;
		return ServiceEmailVerification::verification_otp($email,$r->otp,'C');

	}
	public function send_email_verification() {
		$email=Auth::user()->email;
		ServiceEmailVerification::send_email($email, 'C');	
		
		return Response::success('OTP has been send to your email, please check your email');

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
			$r->email, $r->otp, 'C', true, $r->new_password
		);
	}

	public function login(Request $r) {
		$auth_data = $r->only('email', 'password');
		$user = Client::where('email', $r->email)->first();

		if (!$user)
			return Response::error("Unauthorized", 401);

		$payload = ['guard' => 'CLIENT'];
			
		if (!$token = auth('client')->claims($payload)->attempt($auth_data))
			return Response::error("Unauthorized", 401);

		$response_data = [
			'user' => $user,
			'email_verified' => true,
			'token' => $token,
			'expires_in' => Auth::factory()->getTTL() * 60
		];

		if (!$user->email_verified_at) {
			ServiceEmailVerification::send_email($user->email, 'C');
			$response_data['email_verified'] = false;
		}
			
		return Response::successWithData("Login Successfully", $response_data);
	}

	public function check() {
		return Response::successWithData('Verified Account', Auth::user());
	}
}
