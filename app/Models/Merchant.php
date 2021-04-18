<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
	protected $fillable = [
		'name', 'description', 'address', 'phone_number',
		'bank', 'operator_id', 'city_id'
	];

	public function operator() {
		return $this->belongsTo(Operator::class);
	}

	public function city() {
		return $this->belongsTo(City::class);
	}

	public function merchant_operator() {
		return $this->hasMany(MerchantOperator::class);
	}

	public function merchant_image() {
		return $this->hasMany(MerchantImage::class);
	}

	public function fish_merchant() {
		return $this->hasMany(FishMerchant::class);
	}

	public function transaction() {
		return $this->hasMany(Transaction::class);
	}

	public function review() {
		return $this->hasMany(Review::class);
	}
}
