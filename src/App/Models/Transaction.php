<?php

namespace Masmaleki\Calculator\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $casts = [
        'date' => 'date',
        'commission' => 'decimal:2'
    ];
    public int $id;
    public int $client_id;
    public float $amount;
    public string $currency;
    public mixed $date;
    public string $type;
    public string $client_type;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

    }
    protected $guarded = [];
}
