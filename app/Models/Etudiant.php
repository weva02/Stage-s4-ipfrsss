<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'nni',
        'nomprenom',
        'nationalite',
        'diplome',
        'genre',
        'lieunaissance',
        'adress',
        'age',
        'email',
        'phone',
        'wtsp',
        'id_country'
    ];

    // public function etudiants(){
    //     return $this->hasMany(Etudiant::class);
    // }
}
