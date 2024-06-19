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
use Illuminate\Support\Facades\Log;

class SessionsController extends Component
{
    public function list_session()
    {
        $sessions = Sessions::with('etudiants', 'formation')->paginate(4);
        $formations = Formations::all();
        $modes_paiement = ModePaiement::all();
        return view('livewire.example-laravel.sessions-management', compact('sessions', 'formations', 'modes_paiement'));
    }

    public function addStudentToSession(Request $request, $sessionId)
    {
        $request->validate([
            'student_id' => 'required|exists:etudiants,id',
            'montant_paye' => 'required|numeric',
            'mode_paiement' => 'required|exists:modes_paiement,id',
            'date_paiement' => 'required|date',
        ]);

        try {
            $session = Sessions::findOrFail($sessionId);
            $studentId = $request->student_id;

            $session->etudiants()->attach($studentId, [
                'date_paiement' => $request->date_paiement,
                'montant_paye' => $request->montant_paye,
                'mode_paiement_id' => $request->mode_paiement,
            ]);

            $paiement = new Paiement([
                'etudiant_id' => $studentId,
                'session_id' => $sessionId,
                'prix_reel' => $session->formation->prix,
                'montant_paye' => $request->montant_paye,
                'mode_paiement_id' => $request->mode_paiement,
                'date_paiement' => $request->date_paiement,
            ]);
            $paiement->save();

            return response()->json(['success' => 'Étudiant et paiement ajoutés avec succès']);
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error adding student to session: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'ajout de l\'étudiant et du paiement'], 500);
        }
    }

    public function addPayment(Request $request, $etudiantId, $sessionId)
    {
        try {
            $validatedData = $request->validate([
                'montant_paye' => 'required|numeric',
                'mode_paiement' => 'required|exists:modes_paiement,id',
                'date_paiement' => 'required|date',
            ]);

            $etudiant = Etudiant::find($etudiantId);
            $session = Sessions::find($sessionId);

            if ($etudiant && $session) {
                $existingRelation = $etudiant->sessions()->where('session_id', $sessionId)->first();
                if ($existingRelation) {
                    $newMontantPaye = $existingRelation->pivot->montant_paye + $validatedData['montant_paye'];
                    $newResteAPayer = $existingRelation->pivot->prix_reel - $newMontantPaye;

                    $etudiant->sessions()->updateExistingPivot($sessionId, [
                        'montant_paye' => $newMontantPaye,
                        'reste_a_payer' => $newResteAPayer,
                        'mode_paiement_id' => $validatedData['mode_paiement'],
                        'date_paiement' => $validatedData['date_paiement']
                    ]);

                    return response()->json(['success' => true]);
                }
            }

            return response()->json(['error' => 'Étudiant ou session introuvable.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSessionContents($sessionId)
    {
        $session = Sessions::with(['etudiants.paiements'])->find($sessionId);

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $etudiants = $session->etudiants->map(function($etudiant) {
            $etudiant->telephone = $etudiant->telephone;
            $etudiant->email = $etudiant->email;
            $etudiant->paiements = $etudiant->paiements->map(function($paiement) {
                $paiement->prix_reel = $paiement->prix_reel ?? 0;
                $paiement->montant_paye = $paiement->montant_paye ?? 0;
                $paiement->date_paiement = $paiement->date_paiement ?? '';
                return $paiement;
            });
            return $etudiant;
        });

        return response()->json(['etudiants' => $etudiants]);
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

    public function getFormationDetails($id)
    {
        $formation = Formations::find($id);
        return response()->json(['formation' => $formation]);
    }


    public function searchStudentByPhone(Request $request)
    {
        $phone = $request->phone;
        $student = Etudiant::where('phone', $phone)->first();
        return response()->json(['student' => $student]);
    }


    public function addProfToSession(Request $request, $sessionId)
    {
        $request->validate([
            'prof_id' => 'required|exists:professeurs,id',
        ]);
    
        try {
            $session = Sessions::findOrFail($sessionId);
            $session->professeurs()->attach($request->prof_id);
            return response()->json(['success' => 'Professeur ajouté à la formation avec succès']);
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
