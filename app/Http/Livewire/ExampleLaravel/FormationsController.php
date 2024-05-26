<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Formations;

class FormationsController extends Component
{
    public function liste_formation()
    {
        $formation = Formations::paginate(4);
        return view('livewire.example-laravel.formations-management', compact('formations'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'duree' => 'required|string|max:255',
            // 'prix' => 'required|integer|max:255'

        ]);

        $formation = new Formations([
            'code' => $request->code,
            'nom' => $request->nom,
            'duree' => $request->duree,
            // 'prix' => $request->prix

        ]);
        
        if ($formation->save()) {
            return response()->json(['status' => 200, 'message' => 'Formations ajouté avec succès.']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Erreur lors de l\'ajout du Formations.']);
        }
    }
    public function delete_formation($id){
        $formation = Formations::find($id);
        if ($formation) {
            $formation->delete();
            return redirect()->back()->with('status', 'Formations supprimé avec succès');
        } else {
            return redirect()->back()->with('status', 'Formations non trouvé');
        }
    }
    public function update(Request $request, $id)
    {
        $formation = Formations::find($id);
        if ($formation) {
            $formation->update($request->all());
            return response()->json(['success' => 'Formation modifié avec succès!']);
        } else {
            return response()->json(['error' => 'Formation non trouvé'], 404);
        }
    }

    public function render()
    {
        $formations = Formations::paginate(4);
        return view('livewire.example-laravel.formations-management', compact('formations'));
    }
}
