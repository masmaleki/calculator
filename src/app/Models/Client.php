<?php

namespace Masmaleki\Calculator\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

    }
    protected $guarded = [];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }



}
