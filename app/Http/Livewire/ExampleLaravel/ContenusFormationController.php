<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use App\Models\ContenusFormation;
use App\Models\Formations;
use Livewire\Component;

class ContenusFormationController extends Component
{
    public function liste_contenue()
    {
        $contenues = ContenusFormation::with('formation')->paginate(4);
        $formations = Formations::all();
        return view('livewire.example-laravel.contenusformation-management', compact('contenues', 'formations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numchap' => 'required|string',
            'numunite' => 'required|string',
            'description' => 'required|string',
            'nombreheures' => 'required|integer',
            'formation_id' => 'required|exists:formations,id',
        ]);

        try {
            ContenusFormation::create([
                'numchap' => $request->numchap,
                'numunite' => $request->numunite,
                'description' => $request->description,
                'nombreheures' => $request->nombreheures,
                'formation_id' => $request->formation_id,
            ]);

            return response()->json(['success' => 'Contenu créé avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'numchap' => 'required|string',
            'numunite' => 'required|string',
            'description' => 'required|string',
            'nombreheures' => 'required|integer',
            'formation_id' => 'required|exists:formations,id',
        ]);

        try {
            $contenue = ContenusFormation::findOrFail($id);
            $contenue->update($validated);

            return response()->json(['success' => 'Contenu modifié avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function delete_contenue($id)
    {
        $contenue = ContenusFormation::findOrFail($id);
        $contenue->delete();

        return response()->json(['success' => 'Contenu supprimé avec succès']);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $contenues = ContenusFormation::with('formation')
            ->where('numchap', 'like', "%$search%")
            ->orWhere('numunite', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orWhere('nombreheures', 'like', "%$search%")
            ->paginate(4);

        $formations = Formations::all();
        return view('livewire.example-laravel.contenusformation-management', compact('contenues', 'formations', 'search'));
    }

    public function render()
    {
        $contenues = ContenusFormation::with('formation')->paginate(4);
        $formations = Formations::all();
        return view('livewire.example-laravel.contenusformation-management', compact('contenues', 'formations'));
    }
}
