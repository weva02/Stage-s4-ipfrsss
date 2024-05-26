<?php

namespace App\Exports;

use App\Models\Professeur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProfesseurExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Professeur::all(['id', 'image', 'nomprenom', 'nationalite', 'email', 'diplome', 'phone', 'wtsp', 'typeymntprof_id']);
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
            'image',
            'Nom & Prénom',
            'Nationalité',
            'Email',
            'Diplôme',
            'Portable',
            'WhatsApp',
            'Type de paiement',
            // Ajoutez d'autres noms de colonnes selon votre modèle
        ];
    }
}

