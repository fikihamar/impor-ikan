<?php

namespace App\Http\Controllers\Helper;

use App\Models\Transaction;

class TransactionHelper {
  public static function change_status_transaction($id, $status) {
    if (!in_array($status, ['WP', 'CP', 'OP', 'SO', 'OA']))
      return 400;

    $data = Transaction::find($id);
    $data->status = $status;
    $data->save();

    return 200;
  }
}