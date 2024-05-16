<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Professeur;

class ProfesseurController extends Component
{
    public function liste_prof()
    {
        $profs = Professeur::paginate(4);
        return view('livewire.example-laravel.prof-management', compact('profs'));
    }
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'nomprenom' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'dipleme' => 'required|string|max:255',
    //         'phone' => 'required|integer|max:255',
    //         'wtsp' => 'required|integer|max:255',
    //         'typeymntprof_id' => 'required|string|max:255',

    //     ],
    //     [
    //         'nomprenom.required' => 'Veuillez entrer le nom et le prénom du professeur',
    //         'email.required' => 'Veuillez entrer l\'email du professeur',
    //         'dipleme.required' => 'Veuillez entrer la dipleme du professeur',
    //         'phone.required' => 'Veuillez entrer le numéro de téléphone du professeur',
    //         'wtsp.required' => 'Veuillez entrer le numéro de WhatsApp du professeur',
    //     ]);

    //     $prof = new Professeur([
    //         'nomprenom' => $validatedData['nomprenom'],
    //         'email' => $validatedData['email'],
    //         'dipleme' => $validatedData['dipleme'],
    //         'phone' => $validatedData['phone'],
    //         'wtsp' => $validatedData['wtsp'],
    //         'typeymntprof_id' => $validatedData['typeymntprof_id'],


    //     ]);
    //     $prof->save();

    //     return response()->json(['success' => 'Professeur enregistré avec succès!']);
    //     // Après l'ajout réussi
    //     // return response()->json(['success' => true, 'message' => 'Élément ajouté avec succès', 'redirect' => route('etudiant-management')]);

    // }
    public function store(Request $request)
    {
        $request->validate([
            'nomprenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'diplome' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'wtsp' => 'required|string|max:20',
            'type' => 'required|string|max:50'
        ]);

        $prof = new Professeur([
            'nomprenom' => $request->nomprenom,
            'email' => $request->email,
            'diplome' => $request->diplome,
            'phone' => $request->phone,
            'wtsp' => $request->wtsp,
            'typeymntprof_id' => $request->type
        ]);
        
        if ($prof->save()) {
            return response()->json(['status' => 200, 'message' => 'Professeur ajouté avec succès.']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Erreur lors de l\'ajout du professeur.']);
        }
    }
    public function delete_prof($id){
        $prof = Professeur::find($id);
        if ($prof) {
            $prof->delete();
            return redirect()->back()->with('status', 'Professeur supprimé avec succès');
        } else {
            return redirect()->back()->with('status', 'Professeur non trouvé');
        }
    }
    // public function update(Request $request, $id)
    // {
    //     $prof = Professeur::find($id);
    //     if ($prof) {
    //         $prof->update($request->all());
    //         return response()->json(['success' => 'Professeur modifié avec succès!']);
    //     } else {
    //         return response()->json(['error' => 'Professeur non trouvé'], 404);
    //     }
    // }
    // public function update(Request $request, $id)
    // {
    //     $etudiant = Professeur::find($id);
    //     if ($etudiant) {
    //         $etudiant->update($request->all());
    //         return response()->json(['success' => 'Professeur modifié avec succès!']);
    //     } else {
    //         return response()->json(['error' => 'Professeur non trouvé'], 404);
    //     }
    // }
    public function update(Request $request, $id)
{
    $prof = Professeur::find($id);
    if ($prof) {
        $prof->update($request->all());
        return response()->json(['success' => 'Professeur modifié avec succès!']);
    } else {
        return response()->json(['error' => 'Professeur non trouvé'], 404);
    }
}

    public function render()
    {
        $profs = Professeur::paginate(4);
        return view('livewire.example-laravel.prof-management', compact('profs'));
    }
}
