<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticate;

class Operator extends Authenticate implements JWTSubject
{
	use Notifiable;

	protected $fillable = [
		'name', 'email', 'password'
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

	public function merchant() {
		return $this->hasMany(Merchant::class);
	}
}
