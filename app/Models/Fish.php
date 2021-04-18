<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fish extends Model
{
	protected $fillable = [
		'name', 'description', 'type', 'price',
		'min_order'
	];

	public function fish_image() {
		return $this->hasMany(FishImage::class);
	}

	public function fish_merchant() {
		return $this->hasMany(FishMerchant::class);
	}
}
