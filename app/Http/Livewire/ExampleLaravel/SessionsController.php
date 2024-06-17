<?php

namespace App\Http\Livewire\ExampleLaravel;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Formations;
use App\Models\Sessions;
use App\Models\Etudiant;
use App\Models\Paiement;

use App\Exports\FormationsExport;
use App\Exports\SessionsExport;
use App\Models\ModePaiement;
use Maatwebsite\Excel\Facades\Excel;

class SessionsController extends Component
{


    public function list_session()
    {
        $sessions = Sessions::with('etudiants', 'formation')->paginate(4);
        $formations = Formations::all();
        $modes_paiement = ModePaiement::all();
        return view('livewire.example-laravel.sessions-management', compact('sessions', 'formations', 'modes_paiement'));
    }


    public function getFormationDetails($id) {
        $formation = Formations::find($id);
        return response()->json(['formation' => $formation]);
    }
    
    public function addStudentToSession(Request $request, $etudiantId, $sessionId) {
        $etudiant = Etudiant::find($etudiantId);
        $session = Sessions::find($sessionId);
    
        if ($etudiant && $session) {
            $formation = $session->formation;
            $etudiant->sessions()->attach($sessionId, [
                'prix_reel' => $formation->prix,
                'montant_paye' => $request->montant_paye,
                'reste_a_payer' => $formation->prix - $request->montant_paye,
                'mode_paiement' => $request->mode_paiement,
                'date_paiement' => $request->date_paiement
            ]);
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'error' => 'Etudiant ou session introuvable.']);
    }
    
    public function addPayment(Request $request, $etudiantId, $sessionId) {
        $etudiant = Etudiant::find($etudiantId);
        $session = Sessions::find($sessionId);
    
        if ($etudiant && $session) {
            $existingRelation = $etudiant->sessions()->where('session_id', $sessionId)->first();
            if ($existingRelation) {
                $newMontantPaye = $existingRelation->pivot->montant_paye + $request->montant_paye;
                $etudiant->sessions()->updateExistingPivot($sessionId, [
                    'montant_paye' => $newMontantPaye,
                    'reste_a_payer' => $existingRelation->pivot->prix_reel - $newMontantPaye,
                    'mode_paiement' => $request->mode_paiement,
                    'date_paiement' => $request->date_paiement
                ]);
                return response()->json(['success' => true]);
            }
        }
    
        return response()->json(['success' => false, 'error' => 'Etudiant ou session introuvable.']);
    }
    
    public function searchStudentByPhone(Request $request) {
        $phone = $request->phone;
        $student = Etudiant::where('phone', $phone)->first();
        return response()->json(['student' => $student]);
    }

    
    public function getProfSessionContents($id)
    {
        $session = Sessions::with('professeurs')->find($id);
        if ($session) {
            return response()->json(['prof' => $session->professeurs]);
        } else {
            return response()->json(['error' => 'Formation non trouvée'], 404);
        }
    }

    public function addProfToSession(Request $request, $sessionId)
    {
        $request->validate([
            'prof_id' => 'required|exists:professeurs,id',
        ]);

        try {
            $session = Sessions::findOrFail($sessionId);
            $session->professeurs()->attach($request->prof_id);
            return response()->json(['success' => 'Professeur ajouté à la Formation avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'formation_id' => 'required|exists:formations,id',
        ]);

        try {
            $session = Sessions::create($request->all());
            return response()->json(['success' => 'Session créée avec succès', 'session' => $session]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'nom' => 'required|string',
            'formation_id' => 'required|exists:formations,id',
        ]);

        try {
            $session = Sessions::findOrFail($id);
            $session->update($validated);

            return response()->json(['success' => 'Session modifiée avec succès', 'session' => $session]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $session = Sessions::findOrFail($id);
            $session->delete();
            return response()->json(['success' => 'Session supprimée avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function search6(Request $request)
    {
        if ($request->ajax()) {
            $search6 = $request->search6;
            $sessions = Sessions::where('date_debut', 'like', "%$search6%")
                ->orWhere('date_fin', 'like', "%$search6%")
                ->orWhere('nom', 'like', "%$search6%")
                ->paginate(4);

            $view = view('livewire.example-laravel.sessions-list', compact('sessions'))->render();
            return response()->json(['html' => $view]);
        }
    }


    public function render()
    {
        return $this->list_session();
    }

    public function exportSessions()
{
    return Excel::download(new SessionsExport(), 'sessions.xlsx');
}
    
    
}
