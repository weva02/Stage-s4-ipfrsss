<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Etudiant;

class EtudiantController extends Component
{
    public function liste_etudiant()
    {
        $etudiants = Etudiant::paginate(4);
        return view('livewire.example-laravel.etudiant-management', compact('etudiants'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:255',
        ], [
            'nom.required' => 'Veuillez entrer le nom de l\'étudiant',
            'prenom.required' => 'Veuillez entrer le prénom de l\'étudiant',
            'email.required' => 'Veuillez entrer l\'email de l\'étudiant',
            'telephone.required' => 'Veuillez entrer le numéro de téléphone de l\'étudiant',
        ]);

        $etudiant = new Etudiant([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'email' => $validatedData['email'],
            'telephone' => $validatedData['telephone'],
        ]);
        $etudiant->save();

        return response()->json(['success' => 'Étudiant enregistré avec succès!']);
        // Après l'ajout réussi
        // return response()->json(['success' => true, 'message' => 'Élément ajouté avec succès', 'redirect' => route('etudiant-management')]);

    }
    public function delete_etudiant($id){
        $etudiant = Etudiant::find($id);
        if ($etudiant) {
            $etudiant->delete();
            return redirect()->back()->with('status', 'Etudiant supprimé avec succès');
        } else {
            return redirect()->back()->with('status', 'Étudiant non trouvé');
        }
    }
    public function update(Request $request, $id)
    {
        $etudiant = Etudiant::find($id);
        if ($etudiant) {
            $etudiant->update($request->all());
            return response()->json(['success' => 'Étudiant modifié avec succès!']);
        } else {
            return response()->json(['error' => 'Étudiant non trouvé'], 404);
        }
    }
    public function render()
    {
        $etudiants = Etudiant::paginate(4);
        return view('livewire.example-laravel.etudiant-management', compact('etudiants'));
    }
}
