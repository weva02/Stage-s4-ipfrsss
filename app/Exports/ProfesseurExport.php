<?php


namespace App\Exports;

use App\Models\Typeymntprofs;
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
        return Professeur::with('country')
            ->get()
            ->map(function ($prof) {
                return [
                    'ID' => $prof->id,
                    'Nom & Prénom' => $prof->nomprenom,
                    'Nationalité' => $prof->country ? $prof->country->name : '',
                    'Email' => $prof->email,
                    'Diplôme' => $prof->diplome,
                    'Portable' => $prof->phone,
                    'WhatsApp' => $prof->wtsp,
                    'Type de Contrat' => $prof->types ? $prof->types->type : '',

                ];
            });
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
            'Nom & Prénom',
            'Nationalité',
            'Email',
            'Diplôme',
            'Portable',
            'WhatsApp',
            'Type de Contrat',

            // Ajoutez d'autres noms de colonnes selon votre modèle
        ];
    }
}

