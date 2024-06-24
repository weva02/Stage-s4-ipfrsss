<?php


namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;

use App\Models\Paiement;
use App\Models\Etudiant;
use App\Models\Sessions;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaiementsExport;


class PaiementController extends Component
{


    // public function list_peiement()
    // {
    //     $paiements = Paiement::with(['etudiant', 'session.formation', 'mode'])->paginate(6);
    //     return view('livewire.example-laravel.paiement-management', compact('paiements'));
    // }

    public function list_paiement()
    {
        $paiements = Paiement::with(['etudiant', 'session.formation', 'mode'])
            ->join('etudiants', 'paiements.etudiant_id', '=', 'etudiants.id')
            ->join('sessions', 'paiements.session_id', '=', 'sessions.id')
            ->orderBy('sessions.nom', 'asc')
            ->orderBy('etudiants.nomprenom', 'asc')
            ->select('paiements.*')
            ->paginate(8);
    
        // Calculer le reste à payer
        foreach ($paiements as $paiement) {
            $montantPayeTotal = Paiement::where('etudiant_id', $paiement->etudiant_id)
                ->where('session_id', $paiement->session_id)
                ->sum('montant_paye');
            $paiement->reste_a_payer = $paiement->prix_reel - $montantPayeTotal;
        }
    
        return view('livewire.example-laravel.paiement-management', compact('paiements'));
    }
    


    public function render(){
        return $this->list_paiement();
    }

    public function exportPaiements()
    {
        return Excel::download(new PaiementsExport, 'paiements.xlsx');
    }


    public function addPaiement(Request $request)
    {
        $validatedData = $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'session_id' => 'required|exists:sessions,id',
            'montant_paye' => 'required|integer',
            'mode_paiement' => 'required|exists:modes_paiement,id',
            'date_paiement' => 'required|date',
        ]);

        $paiement = new Paiement([
            'etudiant_id' => $validatedData['etudiant_id'],
            'session_id' => $validatedData['session_id'],
            'montant_paye' => $validatedData['montant_paye'],
            'mode_paiement_id' => $validatedData['mode_paiement'],
            'date_paiement' => $validatedData['date_paiement']
        ]);

        $paiement->save();

        return response()->json(['success' => true]);
    }

    public function getPaymentsByStudent($etudiantId)
    {
        try {
            $etudiant = Etudiant::with('paiements.session')->findOrFail($etudiantId);
            return response()->json(['paiements' => $etudiant->paiements]);
        } catch (\Exception $e) {
            Log::error('Error fetching payments: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des paiements'], 500);
        }
    }

    public function getPaymentsBySession($sessionId)
    {
        try {
            $session = Sessions::with('paiements.etudiant')->findOrFail($sessionId);
            return response()->json(['paiements' => $session->paiements]);
        } catch (\Exception $e) {
            Log::error('Error fetching payments: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des paiements'], 500);
        }
    }

    public function deletePayment($id)
    {
        try {
            $paiement = Paiement::findOrFail($id);
            $paiement->delete();
            return response()->json(['success' => 'Paiement supprimé avec succès']);
        } catch (\Exception $e) {
            Log::error('Error deleting payment: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la suppression du paiement'], 500);
        }
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'montant_paye' => 'required|integer',
            'mode_paiement_id' => 'required|exists:modes_paiement,id',
            'date_paiement' => 'required|date',
        ]);

        try {
            $paiement = Paiement::findOrFail($id);

            $paiement->update([
                'montant_paye' => $request->montant_paye,
                'mode_paiement_id' => $request->mode_paiement_id,
                'date_paiement' => $request->date_paiement,
            ]);

            return response()->json(['success' => 'Paiement mis à jour avec succès', 'paiement' => $paiement]);
        } catch (\Exception $e) {
            Log::error('Error updating payment: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la mise à jour du paiement'], 500);
        }
    }

    public function listPayments()
    {
        $paiements = Paiement::with('etudiant', 'session')->paginate(10);
        return view('livewire.example-laravel.payment-management', compact('paiements'));
    }

    public function searchPayments(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $paiements = Paiement::with('etudiant', 'session')
                ->where('montant_paye', 'like', "%$search%")
                ->orWhereHas('etudiant', function($query) use ($search) {
                    $query->where('nomprenom', 'like', "%$search%");
                })
                ->orWhereHas('session', function($query) use ($search) {
                    $query->where('nom', 'like', "%$search%");
                })
                ->paginate(10);

            $view = view('livewire.example-laravel.payment-list', compact('paiements'))->render();
            return response()->json(['html' => $view]);
        }
    }
}
