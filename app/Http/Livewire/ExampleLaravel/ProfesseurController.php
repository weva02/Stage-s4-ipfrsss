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
