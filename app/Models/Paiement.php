<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    // Le nom de la table associée au modèle
    protected $table = 'paiements';

    // Les attributs qui sont assignables en masse
    protected $fillable = [
        'etudiant_id',
        'session_id',
        'mode_paiement_id',
        'montant_paye',
    ];

    // Définir les relations avec les autres modèles

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }

    public function session()
    {
        return $this->belongsTo(sessions::class, 'session_id');
    }

    public function modePaiement()
    {
        return $this->belongsTo(ModePaiement::class, 'mode_paiement_id');
    }
}