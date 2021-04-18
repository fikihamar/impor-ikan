<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
	protected $fillable = [
		'quantity', 'fish_merchant_id', 'client_id'
	];

	public function fish_merchant() {
		return $this->belongsTo(FishMerchant::class);
	}

	public function client() {
		return $this->belongsTo(Client::class);
	}
}
