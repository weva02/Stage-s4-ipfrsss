<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'image', 'nni', 'nomprenom', 'diplome', 'genre', 'lieunaissance', 'adress',
        'datenaissance', 'email', 'phone', 'wtsp', 'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function sessions()
    {
        return $this->belongsToMany(Sessions::class, 'etud_session', 'etudiant_id', 'session_id')
                    ->withPivot('date_paiement', 'montant_paye', 'mode_paiement_id', 'reste_a_payer')
                    ->withTimestamps();
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
