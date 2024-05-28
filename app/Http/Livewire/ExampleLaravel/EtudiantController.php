<?php
namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Etudiant;
use App\Models\Country;
use App\Exports\EtudiantExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class EtudiantController extends Component
{
    public function liste_etudiant()
    {
        $etudiants = Etudiant::paginate(4);
        $countries = Country::all();
        return view('livewire.example-laravel.etudiant-management', compact('etudiants', 'countries'));
    }

    public function edit($id)
    {
        $etudiant = Etudiant::findOrFail($id);
        $countries = Country::all();
        return view('livewire.example-laravel.etudiant-management', compact('etudiant', 'countries'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('livewire.example-laravel.etudiant-create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nni' => 'required|integer',
            'nomprenom' => 'required|string',
            'nationalite' => 'required|string',
            'diplome' => 'nullable|string',
            'genre' => 'nullable|string',
            'lieunaissance' => 'nullable|string',
            'adress' => 'nullable|string',
            'age' => 'nullable|integer',
            'email' => 'nullable|email',
            'phone' => 'required|integer',
            'wtsp' => 'nullable|integer',
        ]);

        $input = $request->all();

        try {
            $imageName = $request->image->getClientOriginalName();
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

            return redirect()->route('etudiant-management')->with('success', 'Successfully to create new Etudiant');
        } catch (\Throwable $th) {
            return redirect()->route('etudiant-management')->with('error', $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $etudiant = Etudiant::find($id);
        if (!$etudiant) {
            return response()->json(['error' => 'Étudiant non trouvé'], 404);
        }

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nni' => 'required|integer',
            'nomprenom' => 'required|string',
            'nationalite' => 'required|string',
            'diplome' => 'nullable|string',
            'genre' => 'nullable|string',
            'lieunaissance' => 'nullable|string',
            'adress' => 'nullable|string',
            'age' => 'nullable|integer',
            'email' => 'nullable|email',
            'phone' => 'required|integer',
            'wtsp' => 'nullable|integer',
        ]);

        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $imageName = $request->image->getClientOriginalName();
                $request->image->move(public_path('images'), $imageName);
                $data['image'] = $imageName;
            }

            $etudiant->update($data);
   
            return response()->json(['success' => 'Étudiant modifié avec succès!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function delete_etudiant($id)
    {
        $etudiant = Etudiant::find($id);
        if ($etudiant) {
            $etudiant->delete();
            return redirect()->back()->with('status', 'Etudiant supprimé avec succès');
        } else {
            return redirect()->back()->with('status', 'Étudiant non trouvé');
        }
    }

    public function render()
    {
        $etudiants = Etudiant::paginate(4);
        $countries = Country::all();
        return view('livewire.example-laravel.etudiant-management', compact('etudiants', 'countries'));
    }

    public function export()
    {
        return Excel::download(new EtudiantExport, 'Etudiants.xlsx');
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $etudiants = Etudiant::where(function ($query) use ($search) {
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

        $countries = Country::all();
        return view('livewire.example-laravel.recherche', compact('etudiants', 'search', 'countries'));
    }
}
