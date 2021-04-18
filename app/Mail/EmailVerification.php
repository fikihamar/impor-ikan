<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
	use Queueable, SerializesModels;

	public $data;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->view('Email.Base')
			->from('alfigufron21@gmail.com')
			->subject("Impor Ikan | Verifikasi Email")
			->with([
				'data' => $this->data
			]);
	}
}
