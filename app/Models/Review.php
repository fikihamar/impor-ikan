<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
	protected $fillable = [
		'star', 'description', 'merchant_id',
		'client_id'
	];

	public function merchant() {
		return $this->belongsTo(Merchant::class);
	}

	public function client() {
		return $this->belongsTo(Client::class);
	}
}
