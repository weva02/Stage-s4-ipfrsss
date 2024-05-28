<!DOCTYPE html>
<html>
<head>
    <title>Recherche des Étudiants</title>
    <!-- Inclure le CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Inclure le CSS d'IziToast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

    <!-- Inclure le script de jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Inclure le script d'IziToast -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Résultats des Recherches</h1>

        <!-- Formulaire de recherche -->
        <form action="{{ route('search') }}" method="GET" class="form-inline mb-4">
            <input type="text" name="search" class="form-control mr-2" placeholder="Recherche..." value="{{ request()->input('search') }}">
            <button type="submit" class="btn btn-primary">Chercher</button>
        </form>

        <!-- Affichage des résultats -->
        @if($etudiants->count())
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>NNI</th>
                        <th>Nom Prénom</th>
                        <th>Nationalité</th>
                        <th>Diplôme</th>
                        <th>Genre</th>
                        <th>Lieu de Naissance</th>
                        <th>Adresse</th>
                        <th>Âge</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>WhatsApp</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($etudiants as $etudiant)
                        <tr>
                            <td>{{ $etudiant->id }}</td>
                            <td>{{ $etudiant->nni }}</td>
                            <td>{{ $etudiant->nomprenom }}</td>
                            <td>{{ $etudiant->nationalite }}</td>
                            <td>{{ $etudiant->diplome }}</td>
                            <td>{{ $etudiant->genre }}</td>
                            <td>{{ $etudiant->lieunaissance }}</td>
                            <td>{{ $etudiant->adress }}</td>
                            <td>{{ $etudiant->age }}</td>
                            <td>{{ $etudiant->email }}</td>
                            <td>{{ $etudiant->phone }}</td>
                            <td>{{ $etudiant->wtsp }}</td>
                            <td>
                                <a href="javascript:void(0)" id="edit-etudiant" class="btn btn-info btn-sm">Modifier</a>
                                <a href="/delete-etudiant/{{ $etudiant->id }}" id="delete-etudiant" class="btn btn-danger btn-sm">Supprimer</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination des résultats -->
            {{ $etudiants->appends(['search' => $search])->links() }}
        @else
            <p>Aucun étudiant trouvé.</p>
        @endif
    </div>

    <!-- Inclure le script de Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
