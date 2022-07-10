<?php

namespace Masmaleki\Calculator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Masmaleki\Calculator\App\Models\Client;
use Masmaleki\Calculator\App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Foundation\Application;

class calcController extends Controller
{

    public function calc(Request $request): Factory|View|Application|RedirectResponse
    {
        try {
            if ($request->get('mode') == 'test' || config('calculator.mode') == 'test'){
                if (file_exists(public_path('/vendor/calculator/inputs.csv'))){
                    $inputs = public_path('/vendor/calculator/inputs.csv');
                }else{
                    $inputs = __DIR__.'/../../../resources/assets/inputs.csv';
                }
                $file = fopen($inputs, "r");
            }
            else{
                if (!empty($request->files) && $request->hasFile('csv_file')) {
                    $inputs = $request->file('csv_file');
                    $type = $inputs->getClientOriginalExtension();
                    if ($type <> 'csv') {
                        return redirect()->back()->withErrors('Wrong file extension. Only CSV is allowed');
                    }
                    $file = fopen($inputs, "r");
                }
            }

            $transactions = collect();
            $clients = collect();
            $index = 1;
            while ( ($data = fgetcsv($file, 0, ',') ) !== FALSE) {

                $client = $clients->where('id',$data[1])->first();
                if (! $client){
                    $client = new Client();
                    $client->id = $data[1];
                    $client->type = $data[2];
                    $client->free_commission = true;
                    $client->last_transaction = null;
                    $client->remaining_amount = config('calculator.limit');
                    $clients->push($client);
                }
                $tr = new Transaction();
                $tr->id = $index;
                $tr->date = $data[0];
                $tr->client_id = $client->id;
                $tr->client_type = $client->type;
                $tr->type = $data[3];
                $tr->amount = doubleval( $data[4]);
                $tr->currency = $data[5];
                $transactions->push($tr);
                $index++;
            }
            fclose($file);
            foreach($clients as $client){

                $client_transactions = $transactions->where('client_id',$client->id)->sortBy('date')->all();
                foreach ($client_transactions as $transaction){
                    $commission_fee = config('calculator.commission_fees.'.$client->type.'.'.$transaction->type);
                    $rate = $this->getCalcRate($transaction);
                    $euro_amount = $transaction->amount / $rate;

                    if ($transaction->type == 'withdraw' && $client->type == 'private'){

                        $this->setRemaining($client,$transaction);
                        $client->last_transaction = $transaction->date;

                        if ($euro_amount < $client->remaining_amount){
                            $client->remaining_amount = $client->remaining_amount - $euro_amount;
                            $euro_amount = 0; // for zero calculation
                        }else{
                            $euro_amount = $euro_amount - $client->remaining_amount;
                            $client->remaining_amount = 0;
                        }
                    }

                    $fee = $euro_amount * $commission_fee;
                    if ($fee > 0.1){
                        $transaction->commission = number_format(round($fee,1),2);
                    }else{
                        $transaction->commission = round($fee,2);
                    }

                }
            }

            return view('calculator::result',['transactions' => $transactions]);

        }catch (\Exception $e ){
            return redirect()->back()->withErrors($e->getMessage());
        }

    }

    public function setRemaining($client,$transaction){
        if ($client->last_transaction != null){
            $date = Carbon::parse($client->last_transaction);
            $date2 = Carbon::parse($transaction->date);
            $start = $date->startOfWeek()->toDateTime();
            $end = $date->endOfWeek()->toDateTime();
            $result = $date2->gte($start);
            $result1 = $date2->lte($end);
            if (!$result || !$result1){
                $client->remaining_amount = config('calculator.limit');
            }
        }
    }
    public function getCalcRate($transaction,$mode = null): float|int
    {
        $rates =  $this->getRates();
        if ($mode == 'test' || config('calculator.mode') == 'test'){
            $rate = config('calculator.currencies.'.$transaction->currency);
        }else{
            $rate = $this->getRate($transaction->currency, $rates);
        }
        return $rate;
    }
    public function getRates(){
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, config('calculator.rate_url'));
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $jsonRates = json_decode(curl_exec($curlSession))->rates;
        curl_close($curlSession);
        return $jsonRates;
    }
    public function getRate($currency,$rates){
        return $rates->$currency;
    }
}
