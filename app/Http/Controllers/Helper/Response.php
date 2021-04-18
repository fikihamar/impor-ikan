<?php

namespace App\Http\Controllers\Helper;

class Response {
  public static function success($msg) {
    return response()->json([
      'message' => $msg
    ], 200);
  }

  public static function successWithData($msg, $data) {
    return response()->json([
      'message' => $msg,
      'data' => $data
    ], 200);
  }

  public static function error($msg, $code = 400) {
    return response()->json([
      'message' => $msg,
    ], $code);
  }

  public static function errorWithData($msg, $data, $code = 400) {
    return response()->json([
      'message' => $msg,
      'data' => $data
    ], $code);
  }
}
