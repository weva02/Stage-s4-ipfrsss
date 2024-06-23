<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sessions;
use App\Models\Etudiant;
use App\Models\Professeur;
use Carbon\Carbon;
use DB; // Assurez-vous d'importer le namespace DB

class Dashboard extends Component
{
    public $sessionsEnCours;
    public $sessionsTerminees;
    public $nombreEtudiants;
    public $nombreProfesseurs;
    public $etudiantsParSession;
    public $professeursParSession;
    public $etudiantsEnCours;
    public $montantTotalFormationsEnCours;
    public $montantPaye;
    public $resteAPayer;

    public function mount()
    {
        $this->sessionsEnCours = Sessions::where('date_debut', '<=', Carbon::now())
                                         ->where('date_fin', '>=', Carbon::now())
                                         ->count();

        $this->sessionsTerminees = Sessions::where('date_fin', '<', Carbon::now())->count();
        
        $this->nombreEtudiants = Etudiant::count(); // Calcul du nombre total d'étudiants
        $this->nombreProfesseurs = Professeur::count(); // Calcul du nombre total de professeurs

        $this->etudiantsParSession = Sessions::withCount('etudiants')->get(); // Nombre d'étudiants par session
        $this->professeursParSession = Sessions::withCount('professeurs')->get(); // Nombre de professeurs par session
        
        $this->etudiantsEnCours = Etudiant::whereHas('sessions', function($query) {
            $query->where('date_debut', '<=', Carbon::now())
                  ->where('date_fin', '>=', Carbon::now());
        })->count(); // Nombre d'étudiants en cours

        // Calcul du montant total des formations en cours
        $this->montantTotalFormationsEnCours = Sessions::join('formations', 'sessions.formation_id', '=', 'formations.id')
                                                       ->where('sessions.date_debut', '<=', Carbon::now())
                                                       ->where('sessions.date_fin', '>=', Carbon::now())
                                                       ->sum('formations.prix');
        
        // Calcul du montant payé et du reste à payer
        $paiements = DB::table('paiements')
                       ->join('sessions', 'paiements.session_id', '=', 'sessions.id')
                       ->join('formations', 'sessions.formation_id', '=', 'formations.id')
                       ->where('sessions.date_debut', '<=', Carbon::now())
                       ->where('sessions.date_fin', '>=', Carbon::now())
                       ->select(DB::raw('SUM(paiements.montant_paye) as montant_paye, SUM(formations.prix) as montant_total'))
                       ->first();
        
        $this->montantPaye = $paiements->montant_paye ?? 0;
        $this->resteAPayer = ($paiements->montant_total ?? 0) - $this->montantPaye;
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'sessionsEnCours' => $this->sessionsEnCours,
            'sessionsTerminees' => $this->sessionsTerminees,
            'nombreEtudiants' => $this->nombreEtudiants,
            'nombreProfesseurs' => $this->nombreProfesseurs,
            'etudiantsParSession' => $this->etudiantsParSession,
            'professeursParSession' => $this->professeursParSession,
            'etudiantsEnCours' => $this->etudiantsEnCours,
            'montantTotalFormationsEnCours' => $this->montantTotalFormationsEnCours,
            'montantPaye' => $this->montantPaye,
            'resteAPayer' => $this->resteAPayer,
        ]);
    }
}
