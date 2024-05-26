<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Professeur;
use App\Models\Typeymntprofs;
use App\Exports\ProfesseurExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log; 

class ProfesseurController extends Component
{
    // public function liste_prof()
    // {
    //     $profs = Professeur::with('typeYmntProf')->paginate(4);
    //     $types = Typeymntprofs::all(); 
    //     return view('livewire.example-laravel.prof-management', compact('profs', 'types'));
    // }

    public function liste_prof()
    {
        $profs = Professeur::paginate(4);
        return view('livewire.example-laravel.prof-management', compact('profs'));
    }
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nomprenom' => 'required|string',
            'nationalite' => 'required|string',
            'email' => 'required|email',
            'diplome' => 'required|string',
            'phone' => 'required|integer',
            'wtsp' => 'required|integer',
            'type' => 'required|string|max:50'
        ]);

        $input = $request->all();
  
       
        try {
                  // fungsi dibawah digunakan untuk mengambil nama file
                    $imageName =  $request->image->getClientOriginalName();
                   // fungsi move untuk mengupload file ke lokal folder public
                    $request->image->move(public_path('images'),$imageName);
        
                    Professeur::create([
                        'image'=>$imageName,
                        'nomprenom'=>$request->nomprenom,
                        'nationalite'=>$request->nationalite,
                        'email'=>$request->email,
                        'diplome'=>$request->diplome,
                        'phone'=>$request->phone,
                        'wtsp'=>$request->wtsp,
                        'typeymntprof_id' => $request->type
        
                    ]);
                    return redirect()->route('prof-management')->with('success','Successfully to create new Professeur');
                        } catch (\Throwable $th) {
                            //throw $th;
                            return redirect()->route('prof-management')->with('error',$th->getMessage());
                        }

    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'nomprenom' => 'required|string|max:255',
    //         'nationalite' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'diplome' => 'required|string|max:255',
    //         'phone' => 'required|string|max:20',
    //         'wtsp' => 'required|string|max:20',
    //         'type' => 'required|string|max:50'
    //     ]);

    //     // $prof = new Professeur([
    //     //     'nomprenom' => $request->nomprenom,
    //     //     'nationalite' => $request->nationalite,
    //     //     'email' => $request->email,
    //     //     'diplome' => $request->diplome,
    //     //     'phone' => $request->phone,
    //     //     'wtsp' => $request->wtsp,
    //     //     'typeymntprof_id' => $request->type
    //     // ]);
        
    //     // if ($prof->save()) {
    //     //     return response()->json(['status' => 200, 'message' => 'Professeur ajouté avec succès.']);
    //     // } else {
    //     //     return response()->json(['status' => 400, 'message' => 'Erreur lors de l\'ajout du professeur.']);
    //     // }
    //     $input = $request->all();

    //     try {
    //         // fungsi dibawah digunakan untuk mengambil nama file
    //           $imageName =  $request->image->getClientOriginalName();
    //          // fungsi move untuk mengupload file ke lokal folder public
    //           $request->image->move(public_path('images'),$imageName);
  
    //           Professeur::create([
    //               'image'=>$imageName,
    //               'nomprenom'=>$request->nomprenom,
    //               'nationalite'=>$request->nationalite,
    //               'email'=>$request->email,
    //               'diplome'=>$request->diplome,
    //               'phone'=>$request->phone,
    //               'wtsp'=>$request->wtsp,
  
    //           ]);
    //           return redirect()->route('prof-management')->with('success','Successfully to create new Professeur');
    //               } catch (\Throwable $th) {
    //                   //throw $th;
    //                   return redirect()->route('prof-management')->with('error',$th->getMessage());
    //               }




    // }
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
    // public function render()
    // {
    //     $profs = Professeur::paginate(4);
    //     $types = Typeymntprofs::all(); 
    //     return view('livewire.example-laravel.prof-management', compact('profs', 'types'));
    // }

    public function search(Request $request)
    {
        // $query = $request->input('query');
        $search = $request->search;
        $profs =Professeur::where(function($query) use ($search){

            $query->where('id', 'like', "%$search%")
            ->orWhere('nomprenom', 'like', "%$search%")
            ->orWhere('nationalite', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('diplome', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->orWhere('wtsp', 'like', "%$search%")
            ->orWhere('typeymntprof_id', 'like', "%$search%");


        })
        // ->get();
        ->
        paginate(10);
        return view('livewire.example-laravel.prof-management', compact('profs','search'));


    }


    public function export()
    {
        return Excel::download(new ProfesseurExport, 'professeurs.xlsx');
    }
}
