<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Formations;
use App\Exports\FormationsExport;
use Maatwebsite\Excel\Facades\Excel;

class FormationsController extends Component
{
    public function liste_formation()
    {
        $formations = Formations::paginate(4);
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
            return response()->json(['status' => 200, 'message' => 'Formation ajoutée avec succès.']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Erreur lors de l\'ajout de la formation.']);
        }
    }

    public function delete_formation($id)
    {
        $formation = Formations::find($id);
        if ($formation) {
            $formation->delete();
            return redirect()->back()->with('status', 'Formation supprimée avec succès');
        } else {
            return redirect()->back()->with('status', 'Formation non trouvée');
        }
    }

    public function update(Request $request, $id)
    {
        $formation = Formations::find($id);
        if ($formation) {
            $formation->update($request->all());
            return response()->json(['success' => 'Formation modifiée avec succès!']);
        } else {
            return response()->json(['error' => 'Formation non trouvée'], 404);
        }
    }

    public function render()
    {
        $formations = Formations::paginate(4);
        return view('livewire.example-laravel.formations-management', compact('formations'));
    }

    public function export()
    {
        return Excel::download(new FormationsExport, 'formations.xlsx');
    }

    public function search2(Request $request)
    {
        $search2 = $request->search1;
        $formations = Formations::where(function ($query) use ($search2) {
            $query->where('id', 'like', "%$search2%")
                ->orWhere('code', 'like', "%$search2%")
                ->orWhere('nom', 'like', "%$search2%")
                ->orWhere('duree', 'like', "%$search2%")
                ->orWhere('prix', 'like', "%$search2%");
        })->paginate(10);

        return view('livewire.example-laravel.recher-for', compact('formations', 'search2'));
    }
}
