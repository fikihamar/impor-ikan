<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	protected $fillable = [
		'code', 'date', 'address', 'proof_image',
		'status', 'note', 'merchant_id', 'client_id'
	];

	public function merchant() {
		return $this->belongsTo(Merchant::class);
	}

	public function client() {
		return $this->belongsTo(Client::class);
	}

	public function transaction_detail() {
		return $this->hasMany(TransactionDetail::class,'transaction_id');
	}
}
