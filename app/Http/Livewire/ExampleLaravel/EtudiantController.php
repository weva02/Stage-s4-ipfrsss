<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Etudiant;
use App\Exports\EtudiantExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class EtudiantController extends Component
{
    public function liste_etudiant()
    {
        $etudiants = Etudiant::paginate(4);
        return view('livewire.example-laravel.etudiant-management', compact('etudiants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nni' => 'required|integer',
            'nomprenom' => 'required|string',
            'nationalite' => 'required|string',
            'diplome' => 'required|string',
            'genre' => 'required|string',
            'lieunaissance' => 'required|string',
            'adress' => 'required|string',
            'age' => 'required|integer',
            'email' => 'required|email',
            'phone' => 'required|integer',
            'wtsp' => 'required|integer',
        ]);

        try {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            Etudiant::create([
                'image' => $imageName,
                'nni' => $request->nni,
                'nomprenom' => $request->nomprenom,
                'nationalite' => $request->nationalite,
                'diplome' => $request->diplome,
                'genre' => $request->genre,
                'lieunaissance' => $request->lieunaissance,
                'adress' => $request->adress,
                'age' => $request->age,
                'email' => $request->email,
                'phone' => $request->phone,
                'wtsp' => $request->wtsp,
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
            'nationalite' => 'required|string',
            'diplome' => 'required|string',
            'genre' => 'required|string',
            'lieunaissance' => 'required|string',
            'adress' => 'required|string',
            'age' => 'required|integer',
            'email' => 'required|email',
            'phone' => 'required|integer',
            'wtsp' => 'required|integer',
        ]);

        try {
            $etudiant = Etudiant::findOrFail($id);

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $etudiant->image = $imageName;
            }

            $etudiant->nni = $validated['nni'];
            $etudiant->nomprenom = $validated['nomprenom'];
            $etudiant->nationalite = $validated['nationalite'];
            $etudiant->diplome = $validated['diplome'];
            $etudiant->genre = $validated['genre'];
            $etudiant->lieunaissance = $validated['lieunaissance'];
            $etudiant->adress = $validated['adress'];
            $etudiant->age = $validated['age'];
            $etudiant->email = $validated['email'];
            $etudiant->phone = $validated['phone'];
            $etudiant->wtsp = $validated['wtsp'];
            $etudiant->save();

            return response()->json(['success' => 'Étudiant modifié avec succès']);
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
        $search = $request->search;
        $etudiants = Etudiant::where(function($query) use ($search) {
            $query->where('id', 'like', "%$search%")
                ->orWhere('nni', 'like', "%$search%")
                ->orWhere('nomprenom', 'like', "%$search%")
                ->orWhere('nationalite', 'like', "%$search%")
                ->orWhere('diplome', 'like', "%$search%")
                ->orWhere('genre', 'like', "%$search%")
                ->orWhere('lieunaissance', 'like', "%$search%")
                ->orWhere('adress', 'like', "%$search%")
                ->orWhere('age', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('wtsp', 'like', "%$search%");
        })->paginate(10);

        return view('livewire.example-laravel.etudiant-management', compact('etudiants', 'search'));
    }

    public function render()
    {
        $etudiants = Etudiant::paginate(4);
        return view('livewire.example-laravel.etudiant-management', compact('etudiants'));
    }

    public function export()
    {
        return Excel::download(new EtudiantExport, 'Etudiants.xlsx');
    }
}
