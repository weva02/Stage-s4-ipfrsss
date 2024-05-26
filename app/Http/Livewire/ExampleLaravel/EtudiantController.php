<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Etudiant;
use App\Exports\EtudiantExport;
use Maatwebsite\Excel\Facades\Excel;

class EtudiantController extends Component
{
    public function liste_etudiant()
    {
        $etudiants = Etudiant::paginate(4);
        return view('livewire.example-laravel.etudiant-management', compact('etudiants'));
    }

    public function store(Request $request)
    {
        // Validation des données
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

        $input = $request->all();
  
       
        try {
                  // fungsi dibawah digunakan untuk mengambil nama file
                    $imageName =  $request->image->getClientOriginalName();
                   // fungsi move untuk mengupload file ke lokal folder public
                    $request->image->move(public_path('images'),$imageName);
        
                    Etudiant::create([
                        'image'=>$imageName,
                        'nni'=>$request->nni,
                        'nomprenom'=>$request->nomprenom,
                        'nationalite'=>$request->nationalite,
                        'diplome'=>$request->diplome,
                        'genre'=>$request->genre,
                        'lieunaissance'=>$request->lieunaissance,
                        'adress'=>$request->adress,
                        'age'=>$request->age,
                        'email'=>$request->email,
                        'phone'=>$request->phone,
                        'wtsp'=>$request->wtsp,
        
                    ]);
                    return redirect()->route('etudiant-management')->with('success','Successfully to create new Etudiant');
                        } catch (\Throwable $th) {
                            //throw $th;
                            return redirect()->route('etudiant-management')->with('error',$th->getMessage());
                        }

    }
//     public function update(Request $request, $id)
// {
//     // Validation des données
//     $request->validate([
//         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//         'nni' => 'required|integer',
//         'nomprenom' => 'required|string',
//         'nationalite' => 'required|string',
//         'diplome' => 'required|string',
//         'genre' => 'required|string',
//         'lieunaissance' => 'required|string',
//         'adress' => 'required|string',
//         'age' => 'required|integer',
//         'email' => 'required|email',
//         'phone' => 'required|integer',
//         'wtsp' => 'required|integer',
//     ]);

//     try {
//         // Récupération de l'étudiant
//         $etudiant = Etudiant::findOrFail($id);

//         // Suppression de l'ancienne image si elle existe
//         if ($etudiant->image) {
//             Storage::delete('public/images/' . $etudiant->image);
//         }

//         // Enregistrement de la nouvelle image
//         $imageName = $request->image->getClientOriginalName();
//         $request->image->storeAs('public/images', $imageName);

//         // Mise à jour des données de l'étudiant
//         $etudiant->update([
//             'image' => $imageName,
//             'nni' => $request->nni,
//             'nomprenom' => $request->nomprenom,
//             'nationalite' => $request->nationalite,
//             'diplome' => $request->diplome,
//             'genre' => $request->genre,
//             'lieunaissance' => $request->lieunaissance,
//             'adress' => $request->adress,
//             'age' => $request->age,
//             'email' => $request->email,
//             'phone' => $request->phone,
//             'wtsp' => $request->wtsp,
//         ]);

//         return response()->json(['success' => 'Étudiant mis à jour avec succès']);
//     } catch (\Throwable $th) {
//         return response()->json(['error' => $th->getMessage()], 500);
//     }
// }


    public function delete_etudiant($id){
        $etudiant = Etudiant::find($id);
        if ($etudiant) {
            $etudiant->delete();
            return redirect()->back()->with('status', 'Etudiant supprimé avec succès');
        } else {
            return redirect()->back()->with('status', 'Étudiant non trouvé');
        }
    }
    // public function update(Request $request, $id)
    // {

      
    //     if(!$request->image){
    //         Etudiant::where('id',$id)->update([
    //             'nni' => $request->nni,
    //             'nomprenom' => $request->nomprenom,
    //             'nationalite' => $request->nationalite,
    //             'diplome' => $request->diplome,
    //             'genre' => $request->genre,
    //             'lieunaissance' => $request->lieunaissance,
    //             'adress' => $request->adress,
    //             'age' => $request->age,
    //             'email' => $request->email,
    //             'phone' => $request->phone,
    //             'wtsp' => $request->wtsp,
    
    //         ]);
    //     }else{
    //         $imageName =  $request->image->getClientOriginalName();
    //         $request->image->move(public_path('images'),$imageName);
    
    //         Etudiant::where('book_id',$id)->update([
    //             'image'=> $imageName,
    //             'nni' => $request->nni,
    //             'nomprenom' => $request->nomprenom,
    //             'nationalite' => $request->nationalite,
    //             'diplome' => $request->diplome,
    //             'genre' => $request->genre,
    //             'lieunaissance' => $request->lieunaissance,
    //             'adress' => $request->adress,
    //             'age' => $request->age,
    //             'email' => $request->email,
    //             'phone' => $request->phone,
    //             'wtsp' => $request->wtsp,
        
    //         ]);
    //     }
    //     //


    //     return redirect()->route('etudiant-management')->with('success','Successfully update data');

    // }
    public function update(Request $request, $id)
    {
        $etudiant = Etudiant::find($id);
        if ($etudiant) {
            $etudiant->update($request->all());
            return response()->json(['success' => 'Étudiant modifié avec succès!']);
        } else {
            return response()->json(['error' => 'Étudiant non trouvé'], 404);
        }
    }
    public function render()
    {
        $etudiants = Etudiant::paginate(4);
        return view('livewire.example-laravel.etudiant-management', compact('etudiants'));
    }
    public function export(){
        return Excel::download(new EtudiantExport, 'Etudiants.xlsx');
    }
    
    // Dans EtudiantController
    public function search(Request $request)
    {
        // $query = $request->input('query');
        $search = $request->search;
        $etudiants =Etudiant::where(function($query) use ($search){

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


        })
        // ->get();
        ->
        paginate(10);
        return view('livewire.example-laravel.etudiant-management', compact('etudiants','search'));


    }

        


}
