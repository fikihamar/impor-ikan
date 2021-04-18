<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
  protected $fillable = [
		'quantity', 'transaction_id', 'fish_merchant_id'
	];

	public function transaction() {
		return $this->belongsTo(Transaction::class);
	}

	public function fish_merchant() {
		return $this->belongsTo(FishMerchant::class);
	}
}