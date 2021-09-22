<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VtexController extends Controller
{
    private $appKey;
    private $appToken;

    /**
     * VtexController constructor.
     */
    public function __construct()
    {
        $this->appKey = 'vtexappkey-knownonline-IPWBFW';
        $this->appToken = 'CVBSREIACFNEEBYQWRZZEGPEJJMYKTFKZUBGQDIAZICUEGRPXZZYKLWVFWJHSKQJZCFJASASIZAVEUACSWAKTGAOYGATUBIPSTVCBHPFZHPLKBRKWGOVJFPSBQLTRGXH';
    }

    /**
     * @return mixed
     */
    public function sync()
    {
        $apiResponse =Http::withHeaders([
            'X-VTEX-API-AppKey' => $this->appKey,
            'X-VTEX-API-AppToken' => $this->appToken

        ])->get("https://knownonline.vtexcommercestable.com.br/api/oms/pvt/orders",[
            'f_creationDate' => 'creationDate:[2021-01-01T02:00:00.000Z TO 2021-09-23T01:59:59.999Z]'
        ]);
        return $apiResponse->json();
    }
}
