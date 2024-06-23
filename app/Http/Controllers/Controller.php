<?php

namespace App\Http\Controllers;

abstract class Controller
{

    // set midtrans cerdentials

    public function sendRes(array $data, int $code = 200) {
        return response()->json(array_merge([
            'status' => 'success'
        ], $data), $code);
    }

    public function sendFailRes(\Exception $e, int $code = 400) {
        return response()->json([
            'status' => 'failed',
            'message' => $e->getMessage(),
            'error' => $e->getCode()
        ], $code);
    }
}
