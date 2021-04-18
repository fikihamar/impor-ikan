<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorVerification extends Model
{
	public $primaryKey = "email",
		$timestamps = false;

	protected $fillable = [
		'email', 'otp'
	];
}
