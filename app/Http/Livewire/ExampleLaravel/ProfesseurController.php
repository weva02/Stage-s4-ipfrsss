<?php
namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Professeur;
use App\Models\Country;
use App\Models\Typeymntprofs;
use App\Exports\ProfesseurExport;
use Maatwebsite\Excel\Facades\Excel;

class ProfesseurController extends Component
{
    public function liste_prof()
    {
        $profs = Professeur::paginate(4);
        $countries = Country::all();
        $types = Typeymntprofs::all(); // Add this line
        return view('livewire.example-laravel.prof-management', compact('profs', 'countries', 'types'));
    }
    public function typeymntprof()
{
    return $this->belongsTo(Typeymntprofs::class);
}


    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nomprenom' => 'required|string',
            'nattionalite' => 'required|string',
            'email' => 'required|email',
            'diplome' => 'required|string',
            'phone' => 'required|integer',
            'wtsp' => 'required|integer',
            'type' => 'required|string|max:50'
        ]);

        $input = $request->all();
       
        try {
            $imageName = $request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $imageName);
        
            Professeur::create([
                'image' => $imageName,
                'nomprenom' => $request->nomprenom,
                'nattionalite' => $request->nattionalite,
                'email' => $request->email,
                'diplome' => $request->diplome,
                'phone' => $request->phone,
                'wtsp' => $request->wtsp,
                'typeymntprof_id' => $request->type
            ]);

            return redirect()->route('prof-management')->with('success', 'Successfully to create new Professeur');
        } catch (\Throwable $th) {
            return redirect()->route('prof-management')->with('error', $th->getMessage());
        }
    }

    public function delete_prof($id)
    {
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
        $countries = Country::all();
        $types = Typeymntprofs::all(); // Add this line
        return view('livewire.example-laravel.prof-management', compact('profs', 'countries', 'types'));
    }

    public function export()
    {
        return Excel::download(new ProfesseurExport, 'professeurs.xlsx');
    }

    public function search1(Request $request)
    {
        $search1 = $request->search1;
        $profs = Professeur::where(function ($query) use ($search1) {
            $query->where('id', 'like', "%$search1%")
                ->orWhere('nomprenom', 'like', "%$search1%")
                ->orWhere('nationalite', 'like', "%$search1%")
                ->orWhere('diplome', 'like', "%$search1%")
                ->orWhere('genre', 'like', "%$search1%")
                ->orWhere('lieunaissance', 'like', "%$search1%")
                ->orWhere('adress', 'like', "%$search1%")
                ->orWhere('age', 'like', "%$search1%")
                ->orWhere('email', 'like', "%$search1%")
                ->orWhere('phone', 'like', "%$search1%")
                ->orWhere('wtsp', 'like', "%$search1%");
        })->paginate(10);

        $countries = Country::all();
        $types = Typeymntprofs::all(); // Add this line
        return view('livewire.example-laravel.recherche', compact('profs', 'search1', 'countries', 'types'));
    }
}
