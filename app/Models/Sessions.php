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
        return $this->belongsToMany(Etudiant::class, 'etud_session' , 'etudiant_id', 'session_id')->withTimestamps();
    }

    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class, 'prof_session', 'session_id', 'prof_id');
    }
}
