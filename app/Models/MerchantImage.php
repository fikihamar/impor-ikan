<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantImage extends Model
{
	protected $fillable = [
		'filename', 'main_image', 'merchant_id'
	];

	public function merchant() {
		return $this->belongsTo(Merchant::class);
	}
}