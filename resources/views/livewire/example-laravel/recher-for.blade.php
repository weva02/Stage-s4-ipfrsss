<!DOCTYPE html>
<html>
<head>
    <title>Recherche des Étudiants</title>
    <!-- Inclure le meta tag csrf-token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Inclure le CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Inclure le CSS d'IziToast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

    <!-- Inclure le script de jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <!-- Inclure le script de Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Inclure le script d'IziToast -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Résultats des Recherches</h1>

        <!-- Formulaire de recherche -->
        <form action="{{ route('search2') }}" method="GET" class="form-inline mb-4">
            <input type="text" name="search2" class="form-control mr-2" placeholder="Recherche..." value="{{ request()->input('search2') }}">
            <button type="submit" class="btn btn-primary">Chercher</button>
        </form>

        <!-- Affichage des résultats -->
        @if($formations->count())
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Durée</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($formations as $formation)
                        <tr>
                            <td>{{ $formation->id }}</td>
                            <td>{{ $formation->code }}</td>
                            <td>{{ $formation->nom }}</td>
                            <td>{{ $formation->duree }}</td>
                            <td>{{ $formation->prix }}</td>
                            <td>
                                <a href="javascript:void(0)" id="edit-formation" class="btn btn-info btn-sm">Modifier</a>
                                <a href="/delete-formation/{{ $formation->id }}" id="delete-formation" class="btn btn-danger btn-sm">Supprimer</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination des résultats -->
            {{ $formations->appends(['search2' => request()->input('search2')])->links() }}
        @else
            <p>Aucune formation trouvée.</p>
        @endif
    </div>
</body>
</html>
