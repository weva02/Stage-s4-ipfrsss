<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Country;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EtudiantExport;

class EtudiantController extends Component
{
    public function liste_etudiant()
    {
        $etudiants = Etudiant::paginate(4);
        $countries = Country::all();
        return view('livewire.example-laravel.etudiant-management', compact('etudiants', 'countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nni' => 'required|integer',
            'nomprenom' => 'required|string',
            'diplome' => 'required|string',
            'genre' => 'required|string',
            'lieunaissance' => 'required|string',
            'adress' => 'required|string',
            'age' => 'required|integer',
            'email' => 'required|email',
            'phone' => 'required|integer',
            'wtsp' => 'required|integer',
            'country_id' => 'required|exists:countries,id',
        ]);

        try {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            Etudiant::create([
                'image' => $imageName,
                'nni' => $request->nni,
                'nomprenom' => $request->nomprenom,
                'diplome' => $request->diplome,
                'genre' => $request->genre,
                'lieunaissance' => $request->lieunaissance,
                'adress' => $request->adress,
                'age' => $request->age,
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

    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'nni' => 'required|integer',
    //         'nomprenom' => 'required|string',
    //         'diplome' => 'required|string',
    //         'genre' => 'required|string',
    //         'lieunaissance' => 'required|string',
    //         'adress' => 'required|string',
    //         'age' => 'required|integer',
    //         'email' => 'required|email',
    //         'phone' => 'required|integer',
    //         'wtsp' => 'required|integer',
    //         'country_id' => 'required|exists:countries,id',
    //     ]);

    //     try {
    //         $etudiant = Etudiant::findOrFail($id);

    //         if ($request->hasFile('image')) {
    //             $imageName = time() . '.' . $request->image->extension();
    //             $request->image->move(public_path('images'), $imageName);
    //             $etudiant->image = $imageName;
    //         }

    //         $etudiant->update($validated);

    //         return response()->json(['success' => 'Étudiant modifié avec succès']);
    //     } catch (\Throwable $th) {
    //         return response()->json(['error' => $th->getMessage()], 500);
    //     }
    // }

//     public function update(Request $request, $id)
// {
//     $validated = $request->validate([
//         'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//         'nni' => 'required|integer',
//         'nomprenom' => 'required|string',
//         'diplome' => 'required|string',
//         'genre' => 'required|string',
//         'lieunaissance' => 'required|string',
//         'adress' => 'required|string',
//         'age' => 'required|integer',
//         'email' => 'required|email',
//         'phone' => 'required|integer',
//         'wtsp' => 'required|integer',
//         'country_id' => 'required|exists:countries,id',
//     ]);

//     try {
//         $etudiant = Etudiant::findOrFail($id);

//         if ($request->hasFile('image')) {
//             $imageName = time() . '.' . $request->image->extension();
//             $request->image->move(public_path('images'), $imageName);
//             $etudiant->image = $imageName;
//         }

//         $etudiant->update($validated);

//         return response()->json(['success' => 'Étudiant modifié avec succès', 'etudiant' => $etudiant]);
//     } catch (\Throwable $th) {
//         return response()->json(['error' => $th->getMessage()], 500);
//     }
// }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            
             'nni' => 'required|integer',
             'nomprenom' => 'required|string',
             'diplome' => 'required|string',
             'genre' => 'required|string',
            'lieunaissance' => 'required|string',
             'adress' => 'required|string',
            'age' => 'required|integer',
            'email' => 'required|email',
           'phone' => 'required|integer',
            'wtsp' => 'required|integer',
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

            return response()->json(['success' => 'etudiant modifié avec succès', 'etudiant' => $etudiant->load('country', 'typeymntprof')]);
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
                ->orWhere('diplome', 'like', "%$search%")
                ->orWhere('genre', 'like', "%$search%")
                ->orWhere('lieunaissance', 'like', "%$search%")
                ->orWhere('adress', 'like', "%$search%")
                ->orWhere('age', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('wtsp', 'like', "%$search%");
        })->paginate(4);

        $countries = Country::all();
        return view('livewire.example-laravel.etudiant-management', compact('etudiants', 'countries', 'search'));
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