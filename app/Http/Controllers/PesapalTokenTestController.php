<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PesapalService;

class PesapalTokenTestController extends Controller
{
    public function getToken(PesapalService $pesapal)
    {
        return $pesapal->requestAccessToken();
    }
}