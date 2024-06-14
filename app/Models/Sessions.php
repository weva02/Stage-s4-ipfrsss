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
        return $this->belongsTo(Formations::class, 'formation_id');
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'etud_session', 'session_id', 'etudiant_id');
    }
    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class, 'prof_session', 'session_id', 'prof_id');
    }
}
