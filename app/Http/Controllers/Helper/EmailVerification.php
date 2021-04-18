<?php

namespace App\Http\Controllers\Helper;

class EmailVerification {
  public static function generate_otp($length = 6) {
    $permit_chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $otp = "";

    for ($i = 1; $i <= $length; $i++)
      $otp .= $permit_chars[mt_rand(0, strlen($permit_chars) - 1)];

    return $otp;
  }
}