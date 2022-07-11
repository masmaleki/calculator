<?php

namespace Masmaleki\Calculator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Foundation\Application;
use Masmaleki\Calculator\Calculator;

class calcController extends Controller
{

    public function calc(Request $request): Factory|View|RedirectResponse|Application
    {
        if (!empty($request->files) && $request->hasFile('csv_file')) {
            $inputs = $request->file('csv_file');
            $type = $inputs->getClientOriginalExtension();
            if ($type <> 'csv') {
                return redirect()->back()->withErrors('Wrong file extension. Only CSV is allowed');
            }
            $transactions = Calculator::calculate($inputs);
            return view('calculator::result',['transactions' => $transactions]);
        }
        else{

            return redirect()->back()->withErrors('Please upload the transaction files first');

        }

    }
}
