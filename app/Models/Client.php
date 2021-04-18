<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticate;

class Client extends Authenticate implements JWTSubject
{
	use Notifiable;

  protected $fillable = [
		'name', 'email', 'password', 'phone_number',
		'address', 'email_verified_at'
	];

	protected $hidden = [
		'password'
	];

	public function getJWTIdentifier() {
		return $this->getKey();
	}

	public function getJWTCustomClaims() {
		return [];
	}

	public function cart() {
		return $this->hasMany(Cart::class);
	}

	public function transaction() {
		return $this->hasMany(Transaction::class);
	}

	public function review() {
		return $this->hasMany(Review::class);
	}
}
