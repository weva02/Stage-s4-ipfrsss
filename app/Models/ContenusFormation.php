<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContenusFormation extends Model
{
    use HasFactory;

    protected $fillable = [
        'NumChap',
         'NumUnite',
          'description',
            'NombreHeures',
            'id_formation'
    ];

    public function formations()
    {
        return $this->belongsTo(formations::class);
    }
}