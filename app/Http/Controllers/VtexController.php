<?php

namespace App\Http\Controllers;

use App\Order;
use App\Client;
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
        $apiResponse = Http::withHeaders([
            'X-VTEX-API-AppKey' => $this->appKey,
            'X-VTEX-API-AppToken' => $this->appToken

        ])->get("https://knownonline.vtexcommercestable.com.br/api/oms/pvt/orders",[
            'f_creationDate' => 'creationDate:[2021-01-01T02:00:00.000Z TO 2021-09-23T01:59:59.999Z]',
            'per_page' => '50',
            'f_status' => 'ready-for-handling'
        ]);
        return $apiResponse->json();
    }

    public function orders(){
        $orders = $this->sync();
        foreach ($orders['list'] as $value){

            $apiOrders[] = Http::withHeaders([
                'X-VTEX-API-AppKey' => $this->appKey,
                'X-VTEX-API-AppToken' => $this->appToken

            ])->get("https://knownonline.vtexcommercestable.com.br/api/oms/pvt/orders/{$value['orderId']}")->json();
        }
        return $apiOrders;
    }

    /**
     * @return string
     */
    public function storeOrders(){
        $orders = $this->orders();
        foreach($orders as $order){
            if(!(Client::where('client_email',$order['clientProfileData']['email'])->exists())){
                    Client::create([
                    'client_id' => $order['clientProfileData']['id'],
                    'client_first_name' => $order['clientProfileData']['firstName'],
                    'client_last_name' => $order['clientProfileData']['lastName'],
                    'client_email' => $order['clientProfileData']['email']
                ]);
            }

            $order_new = Order::create([
                'order_id' => $order['orderId'],
                'client_id' => $order['clientProfileData']['id'],
                'total' => $order['value'],
                'processed' => 0
            ]);

            foreach($order['items'] as $item){
                $order_new->items()->create([
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);
            }
            foreach($order['paymentData']['transactions'] as $transaction){
                    foreach ($transaction['payments'] as $payment) {
//                        dd($payment);
                        $order_new->payments()->create([
                            'payment_id' => $payment['id'],
                            'paymentSystemName' => $payment['paymentSystemName'],
                            'value' => $payment['value'],
                        ]);
                }
            }
        }

        return 'Ordenes guardadas con éxito';
    }
}
