<?php

namespace Masmaleki\Calculator\Tests;

use Illuminate\Support\Collection;
use Masmaleki\Calculator\Calculator;
use Tests\TestCase;


class TransactionCalculatorTest extends TestCase
{
    /** @test */
    public static function calculationTest()
    {
        $inputs = Calculator::getSampleInputs();
        $transactions = Calculator::calculate($inputs);
        $fees = [];
        foreach ($transactions as $tr){
            $fees[] =  $tr->commission;
        }
        $expected_result = self::getSampleResults();
        static::assertEquals($expected_result, $fees);
    }
    public static function getSampleResults(): array
    {
        return [
            '0.60',
            '3.00',
            '0.00',
            '0.06',
            '1.50',
            '0.00',
            '0.70',
            '0.30',
            '0.30',
            '3.00',
            '0.00',
            '0.00',
            '8612'
        ];
    }
}