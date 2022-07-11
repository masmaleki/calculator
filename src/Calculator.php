<?php

namespace Masmaleki\Calculator;

use Masmaleki\Calculator\App\Models\Client;
use Masmaleki\Calculator\App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;


class Calculator
{
    public static function getSampleInputs(): string
    {
        $inputs = __DIR__.'/resources/assets/inputs.csv';
        if (file_exists(public_path('/vendor/calculator/inputs.csv'))){
            $inputs = public_path('/vendor/calculator/inputs.csv');
        }
        return $inputs;
    }

    public static function calculate($inputs,$mode = null): Collection|RedirectResponse
    {
        try {
            $file = fopen($inputs, "r");
            $transactions = collect();
            $clients = collect();
            $index = 1;
            if ($mode == 'test'){
                $rates = (object) config('calculator.currencies');
            }
            else{
                $rates =  self::getRates();
            }
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
                    
                    $rate = self::getRate($transaction->currency, $rates);
                    $euro_amount = $transaction->amount / $rate;

                    if ($transaction->type == 'withdraw' && $client->type == 'private'){

                        self::setRemaining($client,$transaction);
                        $client->last_transaction = $transaction->date;

                        if ($euro_amount < $client->remaining_amount){
                            $client->remaining_amount = $client->remaining_amount - $euro_amount;
                            $euro_amount = 0; // for zero calculation
                        }else{
                            $euro_amount = $euro_amount - $client->remaining_amount;
                            $client->remaining_amount = 0;
                        }
                    }

                    $fee = $euro_amount * $commission_fee * $rate;
                    if ($transaction->currency == 'JPY'){
                        $fee =  ceil($fee);
                        $transaction->commission = $fee;
                    }elseif ($fee > 0.1){
                        $transaction->commission = round($fee,1);
                    }else{
                        $transaction->commission = round($fee,2);
                    }

                }
            }
            return $transactions;

        }catch (\Exception $e ){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public static function setRemaining($client,$transaction){
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
   
    public static function getRates(){
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, config('calculator.rate_url'));
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $jsonRates = json_decode(curl_exec($curlSession))->rates;
        curl_close($curlSession);
        return $jsonRates;
    }
    public static function getRate($currency,$rates){
        
        return $rates->$currency;
    }
}
