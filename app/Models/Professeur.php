<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'nomprenom',
        'nationalite',
        'email',
        'diplome',
        'phone',
        'wtsp',
        'typeymntprof_id',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function typeymntprof()
    {
        return $this->belongsTo(Typeymntprofs::class);
    }
}