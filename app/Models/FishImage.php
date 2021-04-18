<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishImage extends Model
{
	protected $fillable = [
		'filename', 'main_image', 'fish_id'
	];

	public function fish() {
		return $this->belongsTo(Fish::class);
	}
}
