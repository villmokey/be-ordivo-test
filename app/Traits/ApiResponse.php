<?php

namespace App\Traits;

trait ApiResponse {
  
  public function sendSuccess($data, $message, $code = 200)
  {
    return response()->json([
      'code' => $code,
      'message' => $message,
      'status' => true,
      'data' => $data
    ], $code);
  }

  public function sendError($message, $code = 500)
  {
    return response()->json([
      'code' => $code,
      'message' => $message,
      'status' => false,
      'data' => null
    ], $code);
  }
}