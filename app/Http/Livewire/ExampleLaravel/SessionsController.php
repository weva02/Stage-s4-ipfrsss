<?php

namespace App\Http\Livewire\ExampleLaravel;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Formations;
use App\Models\Sessions;
use App\Models\Etudiant;
use App\Exports\FormationsExport;
use App\Exports\SessionsExport;
use Maatwebsite\Excel\Facades\Excel;

class SessionsController extends Component
{
    public function list_session()
    {
        $sessions = Sessions::with('etudiants', 'professeurs')->paginate(4);
        $formations = Formations::all();
        return view('livewire.example-laravel.sessions-management', compact('sessions', 'formations'));
    }

    
    public function getProfSessionContents($id)
    {
        $session = Sessions::with('professeurs')->find($id);
        if ($session) {
            return response()->json(['prof' => $session->professeurs]);
        } else {
            return response()->json(['error' => 'Formation non trouvée'], 404);
        }
    }

    public function addProfToSession(Request $request, $sessionId)
    {
        $request->validate([
            'prof_id' => 'required|exists:professeurs,id',
        ]);

        try {
            $session = Sessions::findOrFail($sessionId);
            $session->professeurs()->attach($request->prof_id);
            return response()->json(['success' => 'Professeur ajouté à la Formation avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function addStudentToSession(Request $request, $sessionId)
    {
        $request->validate([
            'student_id' => 'required|exists:etudiants,id',
        ]);

        try {
            $session = Sessions::findOrFail($sessionId);
            $session->etudiants()->attach($request->student_id);
            return response()->json(['success' => 'Étudiant ajouté à la session avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function getSessionContents($id)
    {
        $session = Sessions::with('etudiants')->find($id);
        if ($session) {
            return response()->json(['etudiant' => $session->etudiants]);
        } else {
            return response()->json(['error' => 'Session non trouvée'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'formation_id' => 'required|exists:formations,id',
        ]);

        try {
            $session = Sessions::create($request->all());
            return response()->json(['success' => 'Session créée avec succès', 'session' => $session]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'nom' => 'required|string',
            'formation_id' => 'required|exists:formations,id',
        ]);

        try {
            $session = Sessions::findOrFail($id);
            $session->update($validated);

            return response()->json(['success' => 'Session modifiée avec succès', 'session' => $session]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $session = Sessions::findOrFail($id);
            $session->delete();
            return response()->json(['success' => 'Session supprimée avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function search6(Request $request)
    {
        if ($request->ajax()) {
            $search6 = $request->search6;
            $sessions = Sessions::where('date_debut', 'like', "%$search6%")
                ->orWhere('date_fin', 'like', "%$search6%")
                ->orWhere('nom', 'like', "%$search6%")
                ->paginate(4);

            $view = view('livewire.example-laravel.sessions-list', compact('sessions'))->render();
            return response()->json(['html' => $view]);
        }
    }

    

    // public function getSessionContents($id)
    // {
    //     $session = Sessions::with('etudiants')->find($id);
    //     if ($session) {
    //         return response()->json(['etudiant' => $session->etudiants]);
    //     } else {
    //         return response()->json(['error' => 'Session non trouvée'], 404);
    //     }
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nom' => 'required|string',
    //         'date_debut' => 'required|date',
    //         'date_fin' => 'required|date',
    //         'formation_id' => 'required|exists:formations,id',
    //     ]);

    //     try {
    //         Sessions::create([
    //             'nom' => $request->nom,
    //             'date_debut' => $request->date_debut,
    //             'description' => $request->description,
    //             'date_fin' => $request->date_fin,
    //             'formation_id' => $request->formation_id,
    //         ]);

    //         return response()->json(['success' => 'Session créée avec succès']);
    //     } catch (\Throwable $th) {
    //         return response()->json(['error' => $th->getMessage()], 500);
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'date_debut' => 'required|date',
    //         'date_fin' => 'required|date',
    //         'nom' => 'required|string',
    //         'formation_id' => 'required|exists:formations,id',
    //     ]);

    //     try {
    //         $session = Sessions::findOrFail($id);
    //         $session->update($validated);

    //         return response()->json(['success' => 'Session modifiée avec succès', 'session' => $session]);
    //     } catch (\Throwable $th) {
    //         return response()->json(['error' => $th->getMessage()], 500);
    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         $session = Sessions::findOrFail($id);
    //         $session->delete();
    //         return response()->json(['success' => 'Session supprimée avec succès']);
    //     } catch (\Throwable $th) {
    //         return response()->json(['error' => $th->getMessage()], 500);
    //     }
    // }

    // public function search(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $search = $request->search3;
    //         $sessions = Sessions::where('date_debut', 'like', "%$search%")
    //             ->orWhere('date_fin', 'like', "%$search%")
    //             ->orWhere('nom', 'like', "%$search%")
    //             ->paginate(4);

    //         $view = view('livewire.example-laravel.sessions-list', compact('sessions'))->render();
    //         return response()->json(['html' => $view]);
    //     }
    // }

    public function render()
    {
        return $this->list_session();
    }

    public function exportSessions()
{
    return Excel::download(new SessionsExport(), 'sessions.xlsx');
}
    
    
}
