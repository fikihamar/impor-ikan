<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishMerchant extends Model
{
	protected $fillable = [
		'quantity', 'merchant_id', 'fish_id'
	];

	public function merchant() {
		return $this->belongsTo(Merchant::class);
	}

	public function fish() {
		return $this->belongsTo(Fish::class);
	}

	public function cart() {
		return $this->hasMany(Cart::class);
	}

	public function transaction_detail() {
		return $this->hasMany(TransactionDetail::class);
	}
	public function getImageUrlAttribute($value)
    {
        return env("APP_URL"). "/fish_images/" . $this->filename;
    }
}
