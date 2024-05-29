<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use App\Models\Professeur;
use App\Models\Country;
use App\Models\Typeymntprofs;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfesseurExport;

class ProfesseurController extends Component
{
    public function liste_prof()
    {
        $profs = Professeur::paginate(4);
        $countries = Country::all();
        $types = Typeymntprofs::all();
        return view('livewire.example-laravel.prof-management', compact('profs', 'countries', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nomprenom' => 'required|string',
            'email' => 'required|email',
            'diplome' => 'required|string',
            'phone' => 'required|integer',
            'wtsp' => 'required|integer',
            'country_id' => 'required|exists:countries,id',
            'typeymntprof_id' => 'required|exists:typeymntprofs,id',
        ]);

        try {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            Professeur::create([
                'image' => $imageName,
                'nomprenom' => $request->nomprenom,
                'email' => $request->email,
                'diplome' => $request->diplome,
                'phone' => $request->phone,
                'wtsp' => $request->wtsp,
                'country_id' => $request->country_id,
                'typeymntprof_id' => $request->typeymntprof_id,
            ]);

            return response()->json(['success' => 'Professeur créé avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nomprenom' => 'required|string',
            'email' => 'required|email',
            'diplome' => 'required|string',
            'phone' => 'required|integer',
            'wtsp' => 'required|integer',
            'country_id' => 'required|exists:countries,id',
            'typeymntprof_id' => 'required|exists:typeymntprofs,id',
        ]);

        try {
            $prof = Professeur::findOrFail($id);

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $prof->image = $imageName;
            }

            $prof->update($validated);

            return response()->json(['success' => 'Professeur modifié avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function delete_prof($id)
    {
        try {
            $prof = Professeur::findOrFail($id);
            $prof->delete();

            return response()->json(['success' => 'Professeur supprimé avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $profs = Professeur::where(function($query) use ($search) {
            $query->where('id', 'like', "%$search%")
                ->orWhere('nomprenom', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('diplome', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('wtsp', 'like', "%$search%");
        })->paginate(4);

        $countries = Country::all();
        $types = Typeymntprofs::all();
        return view('livewire.example-laravel.prof-management', compact('profs', 'countries', 'search', 'types'));
    }

    public function render()
    {
        $profs = Professeur::paginate(4);
        $countries = Country::all();
        $types = Typeymntprofs::all();
        return view('livewire.example-laravel.prof-management', compact('profs', 'countries' , 'types'));
    }
}
