<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    use HasFactory;

    protected $fillable = ['formation_id', 'nom', 'date_debut', 'date_fin'];

    public function formation()
    {
        return $this->belongsTo(Formations::class);
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'etud_session', 'session_id', 'etudiant_id')
                    ->withPivot('date_paiement', 'montant_paye', 'mode_paiement_id', 'reste_a_payer')
                    ->withTimestamps();
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
