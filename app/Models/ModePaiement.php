<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModePaiement extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    // If your table name does not follow the Laravel convention
    protected $table = 'modes_paiement';
}
