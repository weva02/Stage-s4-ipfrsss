<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomprenom',
        'diplome',
        'email',
        'phone',
        'wtsp',
        'typeymntprof_id',
    ];
}
