<?php

namespace App\Http\Controllers;

use App\Exports\ProfesseurExport;
use App\Exports\EtudiantExport;
use App\Exports\formationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportProfesseurs()
    {
        return Excel::download(new ProfesseurExport, 'professeurs.xlsx');
    }

    public function exportEtudiants()
    {
        return Excel::download(new EtudiantExport, 'etudiants.xlsx');
    }
    public function formationsExport()
    {
        return Excel::download(new formationsExport, 'formations.xlsx');
    }
}
