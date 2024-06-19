<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Country;
use App\Models\Sessions;
use App\Models\Paiement;
use App\Models\ModePaiement;

use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EtudiantExport;

class EtudiantController extends Component
{
    public function liste_etudiant()
    {
        $etudiants = Etudiant::with('sessions')->paginate(4);
        $countries = Country::all();
        return view('livewire.example-laravel.etudiant-management', compact('etudiants', 'countries'));
    }

    public function searchByPhone(Request $request)
    {
        $phone = $request->phone;
        $student = Etudiant::where('phone', $phone)->first();

        if ($student) {
            return response()->json(['student' => $student]);
        } else {
            return response()->json(['error' => 'Étudiant non trouvé'], 404);
        }
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showAddStudentModal($sessionId)
    {
        $modes_paiement = ModePaiement::all();
        return view('add_student_modal', compact('modes_paiement', 'sessionId'));
    }

   

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nni' => 'required|integer',
            'nomprenom' => 'required|string',
            'diplome' => 'nullable|string',
            'genre' => 'required|string',
            'lieunaissance' => 'nullable|string',
            'adress' => 'nullable|string',
            'datenaissance' => 'nullable|date',
            'email' => 'nullable|email',
            'phone' => 'required|integer',
            'wtsp' => 'nullable|integer',
            'country_id' => 'required|exists:countries,id',
        ]);

        try {
            $imageName = $request->hasFile('image') ? time() . '.' . $request->image->extension() : null;

            if ($imageName) {
                $request->image->move(public_path('images'), $imageName);
            }

            Etudiant::create([
                'image' => $imageName,
                'nni' => $request->nni,
                'nomprenom' => $request->nomprenom,
                'diplome' => $request->diplome,
                'genre' => $request->genre,
                'lieunaissance' => $request->lieunaissance,
                'adress' => $request->adress,
                'datenaissance' => $request->datenaissance,
                'email' => $request->email,
                'phone' => $request->phone,
                'wtsp' => $request->wtsp,
                'country_id' => $request->country_id,
            ]);

            return response()->json(['success' => 'Étudiant créé avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nni' => 'required|integer',
            'nomprenom' => 'required|string',
            'diplome' => 'nullable|string',
            'genre' => 'required|string',
            'lieunaissance' => 'nullable|string',
            'adress' => 'nullable|string',
            'datenaissance' => 'nullable|date',
            'email' => 'nullable|email',
            'phone' => 'required|integer',
            'wtsp' => 'nullable|integer',
            'country_id' => 'required|exists:countries,id',
        ]);

        try {
            $etudiant = Etudiant::findOrFail($id);

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('images'), $imageName);
                $validated['image'] = $imageName;
            }

            $etudiant->update($validated);

            return response()->json(['success' => 'Étudiant modifié avec succès', 'etudiant' => $etudiant->load('country')]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function delete_etudiant($id)
    {
        $etudiant = Etudiant::findOrFail($id);
        $etudiant->delete();

        return response()->json(['success' => 'Étudiant supprimé avec succès']);
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $etudiants = Etudiant::where(function ($query) use ($search) {
                $query->where('id', 'like', "%$search%")
                    ->orWhere('nni', 'like', "%$search%")
                    ->orWhere('nomprenom', 'like', "%$search%")
                    ->orWhere('diplome', 'like', "%$search%")
                    ->orWhere('genre', 'like', "%$search%")
                    ->orWhere('lieunaissance', 'like', "%$search%")
                    ->orWhere('adress', 'like', "%$search%")
                    ->orWhere('datenaissance', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('wtsp', 'like', "%$search%");
            })->paginate(4);

            $view = view('livewire.example-laravel.etudiants-list', compact('etudiants'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function export()
    {
        return Excel::download(new EtudiantExport, 'Etudiants.xlsx');
    }

    public function render()
    {
        $etudiants = Etudiant::paginate(4);
        $countries = Country::all();
        return view('livewire.example-laravel.etudiant-management', compact('etudiants', 'countries'));
    }
}
