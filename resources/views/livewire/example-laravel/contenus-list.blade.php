<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom du Chapitre</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom de l'Unité</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre d'heures</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Formation</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($contenues as $contenu)
        <tr>
            <td>{{ $contenu->id }}</td>
            <td>{{ $contenu->nomchap }}</td>
            <td>{{ $contenu->nomunite }}</td>
            <td>{{ $contenu->description }}</td>
            <td>{{ $contenu->nombreheures }}</td>
            <td data-formation-id="{{ $contenu->formation_id }}">{{ $contenu->formation->nom ?? 'N/A' }}</td>
            <td>
                <a href="javascript:void(0)" id="edit-contenu" data-id="{{ $contenu->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                <a href="javascript:void(0)" id="delete-contenu" data-id="{{ $contenu->id }}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $contenues->links() }}