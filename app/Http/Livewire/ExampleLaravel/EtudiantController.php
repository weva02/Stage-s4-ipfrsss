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
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'nni' => 'required|integer|max:255',
    //         'nomprenom' => 'required|string|max:255',
    //         'nationalite' => 'required|string|max:255',
    //         'diplome' => 'required|string|max:255',
    //         'genre' => 'required|string|max:255',
    //         'lieunaissance' => 'required|string|max:255',
    //         'adress' => 'required|string|max:255',
    //         'age' => 'required|integer|max:255',
    //         'email' => 'required|email|max:255',
    //         'phone' => 'required|integer|max:255',
    //         'wtsp' => 'required|integer|max:255',
    //     ], [
    //         'nni.required' => 'Veuillez entrer le NNI de l\'étudiant',
    //         'nomprenom.required' => 'Veuillez entrer le nom et le prénom de l\'étudiant',
    //         'nationalite.required' => 'Veuillez entrer la nationalite de l\'étudiant',
    //         'genre.required' => 'Veuillez entrer le genre de l\'étudiant',
    //         'lieunaissance.required' => 'Veuillez entrer le lieunaissance de l\'étudiant',
    //         'adress.required' => 'Veuillez entrer l\'adress de l\'étudiant',
    //         'age.required' => 'Veuillez entrer l\'age de l\'étudiant',
    //         'email.required' => 'Veuillez entrer l\'email de l\'étudiant',
    //         'phone.required' => 'Veuillez entrer le numéro de téléphone de l\'étudiant',
    //         'wtsp.required' => 'Veuillez entrer le numéro de WhatsApp de l\'étudiant',

    //     ]);

    //     $etudiant = new Etudiant([
    //         'nni' => $validatedData['nni'],
    //         'nomprenom' => $validatedData['nomprenom'],
    //         'nationalite' => $validatedData['nationalite'],
    //         'diplome' => $validatedData['diplome'],
    //         'genre' => $validatedData['genre'],
    //         'lieunaissance' => $validatedData['lieunaissance'],
    //         'adress' => $validatedData['adress'],
    //         'age' => $validatedData['age'],
    //         'email' => $validatedData['email'],
    //         'phone' => $validatedData['phone'],
    //         'wtsp' => $validatedData['wtsp'],
    //     ]);
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'nni' => 'required|integer',
            'nomprenom' => 'required|string|max:255',
            'nationalite' => 'required|string|max:255',
            'diplome' => 'required|string|max:255',
            'genre' => 'required|string|max:1',
            'lieunaissance' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'age' => 'required|integer',
            'email' => 'required|email|max:255',
            'phone' => 'required|integer',
            'wtsp' => 'required|integer',
        ]);

        // Création d'un nouvel étudiant
        $etudiant = new Etudiant([
            'nni' => $validatedData['nni'],
            'nomprenom' => $validatedData['nomprenom'],
            'nationalite' => $validatedData['nationalite'],
            'diplome' => $validatedData['diplome'],
            'genre' => $validatedData['genre'],
            'lieunaissance' => $validatedData['lieunaissance'],
            'adress' => $validatedData['adress'],
            'age' => $validatedData['age'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'wtsp' => $validatedData['wtsp'],
        ]);

        // Sauvegarde dans la base de données
        $etudiant->save();

        // Réponse JSON
        return response()->json(['success' => 'Étudiant enregistré avec succès!']);
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
