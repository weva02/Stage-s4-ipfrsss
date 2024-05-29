<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Formations Management</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if (session('status'))
                <div class="alert alert-success fade-out">
                    {{ session('status')}}
                </div>
                @endif
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#formationAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter une formation
                            </button>
                            <a href="{{ route('formations.export') }}" class="btn btn-success">Exporter formations</a>
                        </div>
                        <form action="/search2" method="get" class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search2" id="search_bar" class="form-control" placeholder="Rechercher..." value="{{ isset($search2) ? $search2 : ''}}">
                                <button type="submit" class="btn btn-primary">Rechercher</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Code</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nom</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Durée</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prix</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
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
                                            <a href="javascript:void(0)" id="edit-formation" data-id="{{ $formation->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                                            <a href="javascript:void(0)" id="delete-formation" data-id="{{ $formation->id }}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $formations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter Formation -->
    <div class="modal fade" id="formationAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter une nouvelle Formation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formation-add-form">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Code:</label>
                            <input type="text" class="form-control" id="new-formation-code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom:</label>
                            <input type="text" class="form-control" id="new-formation-nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="duree" class="form-label">Durée:</label>
                            <input type="number" class="form-control" id="new-formation-duree" name="duree" required>
                        </div>
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix:</label>
                            <input type="number" class="form-control" id="new-formation-prix" name="prix" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="add-new-formation">Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Formation -->
    <div class="modal fade" id="formationEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier Formation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formation-edit-form">
                        @csrf
                        <input type="hidden" id="formation-id" name="id">
                        <div class="mb-3">
                            <label for="code" class="form-label">Code:</label>
                            <input type="text" class="form-control" id="formation-code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom:</label>
                            <input type="text" class="form-control" id="formation-nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="duree" class="form-label">Durée:</label>
                            <input type="number" class="form-control" id="formation-duree" name="duree" required>
                        </div>
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix:</label>
                            <input type="number" class="form-control" id="formation-prix" name="prix" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="formation-update">Modifier</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Ajouter une formation
            $("#add-new-formation").click(function (e) {
                e.preventDefault();
                let form = $('#formation-add-form')[0];
                let data = new FormData(form);

                $.ajax({
                    url: "{{ route('formations.store') }}",
                    type: "POST",
                    data: data,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status == 400) {
                            iziToast.error({
                                title: 'Erreur',
                                message: response.message,
                                position: 'topRight'
                            });
                        } else {
                            iziToast.success({
                                title: 'Succès',
                                message: response.message,
                                position: 'topRight'
                            });
                            $('#formationAddModal').modal('hide');
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                    }
                });
            });

            // Modifier une formation
            $('body').on('click', '#edit-formation', function () {
                var tr = $(this).closest('tr');
                $('#formation-id').val(tr.find("td:nth-child(1)").text());
                $('#formation-code').val(tr.find("td:nth-child(2)").text());
                $('#formation-nom').val(tr.find("td:nth-child(3)").text());
                $('#formation-duree').val(tr.find("td:nth-child(4)").text());
                $('#formation-prix').val(tr.find("td:nth-child(5)").text());

                $('#formationEditModal').modal('show');
            });

            $('body').on('click', '#formation-update', function () {
                var id = $('#formation-id').val();
                var data = {
                    code: $('#formation-code').val(),
                    nom: $('#formation-nom').val(),
                    duree: $('#formation-duree').val(),
                    prix: $('#formation-prix').val(),
                };

                $.ajax({
                    url: '/formations/' + id,
                    type: 'PUT',
                    dataType: 'json',
                    data: data,
                    success: function (response) {
                        $('#formationEditModal').modal('hide');
                        if (response.success) {
                            iziToast.success({
                                message: response.success,
                                position: 'topRight'
                            });
                            location.reload();
                        } else {
                            iziToast.error({
                                message: response.error,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        iziToast.error({
                            message: 'An error occurred: ' + error,
                            position: 'topRight'
                        });
                    }
                });
            });

            // Supprimer une formation
            $('body').on('click', '#delete-formation', function (e) {
                e.preventDefault();
                var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cette formation ?");
                if (confirmation) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '/formations/' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                iziToast.success({
                                    message: response.success,
                                    position: 'topRight'
                                });
                                location.reload();
                            } else {
                                iziToast.error({
                                    message: response.error,
                                    position: 'topRight'
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            iziToast.error({
                                message: 'An error occurred: ' + error,
                                position: 'topRight'
                            });
                        }
                    });
                }
            });

            var alertElement = document.querySelector('.fade-out');
            if (alertElement) {
                setTimeout(function () {
                    alertElement.style.display = 'none';
                }, 2000);
            }
        });
    </script>
</body>
</html>