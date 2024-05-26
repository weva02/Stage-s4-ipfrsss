<?php

namespace App\Exports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EtudiantExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Etudiant::all(['id','nni', 'nomprenom', 'nationalite','diplome','genre', 'lieunaissance', 'adress', 'age', 'email',  'phone', 'wtsp']);
    }

    /**
     * Retourne les en-têtes des colonnes.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'NNI',
            'Nom & Prénom',
            'Nationalité',
            'Diplôme',
            'Genre',
            'Lieu Naissance',
            'Adress',
            'Age',
            'Email',
            'Portable',
            'WhatsApp',
            // Ajoutez d'autres noms de colonnes selon votre modèle
        ];
    }
}

