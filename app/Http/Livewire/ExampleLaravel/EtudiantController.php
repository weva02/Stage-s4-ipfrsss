<?php


namespace App\Http\Livewire\ExampleLaravel;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterRequestUpdate;
use Illuminate\Http\Request;
use Livewire\Component;

use App\Models\Etudiant;


class EtudiantController extends Component
{
    public function liste_etudiant()
    {
        $etudiants = Etudiant::paginate(5);
        return view('livewire.example-laravel.etudiant-management' , compact('etudiants'));
    }
    // public function add_etudiant()
    // {
    //     return view('livewire.example-laravel.add-etudiant');
    // }
    // public function add_etudiant_traitement(Request $request)
    // {
    //     $request->validate([
    //         'nom' => 'required',
    //         'prenom' => 'required',
    //         'email' => 'required',
    //         'telephone' => 'required',
    //     ]);
    //     $etudiant = new Etudiant();
    //     $etudiant->nom = $request->nom;
    //     $etudiant->prenom = $request->prenom;
    //     $etudiant->email = $request->email;
    //     $etudiant->telephone = $request->telephone;
    //     $etudiant->save();

    //     return redirect('/ajouter')->with('status', 'L etudiant a bien ete ajoute avec succes.');

    // }
    // public function update_etudiant($id){
    //     $etudiants = Etudiant::find($id);
    //     return view('livewire.example-laravel.update-etudiant' , compact('etudiants'));

    // }
    // public function update_etudiant_traitement(Request $request){
    //     $request->validate([
    //         'nom' => 'required',
    //         'prenom' => 'required',
    //         'email' => 'required',
    //         'telephone' => 'required',
    //     ]);
    
    //     $etudiant = Etudiant::find($request->id);
    //     $etudiant->nom = $request->nom;
    //     $etudiant->prenom = $request->prenom;
    //     $etudiant->email = $request->email;
    //     $etudiant->telephone = $request->telephone;
    //     $etudiant->update();
    //     return redirect('/etudiant')->with('status', 'L etudiant a bien ete modifier avec succes.');
    // }
    public function delete_etudiant($id){
        $etudiant = Etudiant::find($id);
        $etudiant->delete();
        return redirect('/etudiant')->with('status', 'L etudiant a bien ete supprimer avec succes.');
    }

    // ###############################################################################################
    public  function store(EtudiantController $request){
        $etudiants = Etudiant::create([
           'nom' =>  $request->nom,
            'prenom' =>  $request->prenom,
            'email' =>  $request->email,
            'telephone' =>  $request->telephone,
        ]);
        if($etudiants)
        return response()->json($etudiants);
        else
            return response()->json([
                'status' => false,
                'msg' => 'SomeThing Error Try Again',
            ]);
    }

    public  function edit($id){
        $etudiantData = Etudiant::find($id);
        return response()->json($etudiantData);
    }

    public function update(EtudiantController $request, $id){
        $etudiants = Etudiant::find($id);
        if (!$etudiants)
            return response()->json([
                'status' => false,
                'msg' => 'SomeThing Error Try Again',
            ]);
        else
            $etudiants->update([
                'nom' =>  $request->nomupdate,
                'prenom' =>  $request->prenomupdate,
                'email' =>  $request->emailupdate,
                'telephone' =>  $request->telephoneupdate,
            ]);

            return response()->json($etudiants);

    }
    public function render()
    {
        // Récupérer les étudiants depuis la base de données
        $etudiants = Etudiant::paginate(5);

        // Passer les données des étudiants à la vue
        return view('livewire.example-laravel.etudiant-management', compact('etudiants'));
    }


    
}
