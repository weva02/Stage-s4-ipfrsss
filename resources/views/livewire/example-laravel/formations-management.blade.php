<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Programmes Management</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .imgUpload {
            max-width: 90px;
            max-height: 70px;
            min-width: 50px;
            min-height: 50px;
        }
        .required::after {
            content: " *";
            color: red;
        }
        .form-control {
            border: 1px solid #ccc;
        }
        .form-control:focus {
            border-color: #66afe9;
            outline: 0;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
        }
    </style>
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
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#formationAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter une Programme
                            </button>
                            <a href="{{ route('formations.export') }}" class="btn btn-success">Exporter Programmes</a>
                            <a href="{{ route('contenusformation-management') }}" class="btn bg-gradient-dark">Contenus Programme</a>

                        </div>
                        <form action="/search1" method="get" class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search1" id="search_bar" class="form-control" placeholder="Rechercher..." value="{{ isset($search1) ? $search1 : ''}}">
                         
                            </div>
                        </form>
                    </div>
                    <div class="me-3 my-3 text-end "></div>
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
                                        
                                        <td><a href="javascript:void(0)" id="show-formation" data-id="{{ $formation->id }}" >{{ $formation->nom }}</a></td>

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

    <!-- Add formation -->
    <div class="modal fade" id="formationAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter une nouvelle Programme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formation-add-form">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="code" class="form-label required">Code:</label>
                                <input type="text" class="form-control" id="new-formation-code" placeholder="Code du programme"  name="code">
                                 <div class="text-danger" id="code-warning"></div>

                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label required">Nom :</label>
                                <input type="text" class="form-control" id="new-formation-nom" placeholder="Nom du programme" name="nom">
                            <div class="text-danger" id="nom-warning"></div>

                            </div>
                        </div>
                        <br>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="duree" class="form-label required">Durée:</label>
                                <input type="number" class="form-control" id="new-formation-duree" placeholder="Durée" name="duree">
                            <div class="text-danger" id="duree-warning"></div>

                            </div>
                            <div class="col-md-6">
                                <label for="prix" class="form-label required">Prix:</label>
                                <input type="number" class="form-control" id="new-formation-prix" placeholder="Prix" name="prix">
                            <div class="text-danger" id="prix-warning"></div>

                            </div>
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



    <!-- Modal Détails Formation -->
<div class="modal fade" id="formationDetailModal" tabindex="-1" aria-labelledby="formationDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 15cm;">
            <div class="modal-header">
                <h5 class="modal-title" id="formationDetailModalLabel">Détails de la Programme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="formation-details">
                    <p><strong>Code:</strong> <span id="formation-code-details"></span></p>
                    <p><strong>Nom:</strong> <span id="formation-nom-details"></span></p>
                    <p><strong>Durée:</strong> <span id="formation-duree-details"></span> Heures</p>
                    <p><strong>Prix:</strong> <span id="formation-prix-details"></span> MRU</p>
                    
                    <h4>Contenus de la Programme</h4>
                    <ul id="formation-contents-details"></ul>
                </div>
            </div>
            <div class="modal-footer">
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
                    <h5 class="modal-title" id="exampleModalLabel">Modifier Programme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formation-edit-form">
                        @csrf
                        <input type="hidden" id="formation-id" name="id">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="code" class="form-label required">Code:</label>
                                <input type="text" class="form-control" id="formation-code" placeholder="Code du programme" name="code" required>
                            <div class="text-danger" id="edit-code-warning"></div>

                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label required">Nom:</label>
                                <input type="text" class="form-control" id="formation-nom" placeholder="Nom du programme" name="nom" required>
                            <div class="text-danger" id="edit-nom-warning"></div>

                            </div>
                        </div>
                        <br>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="duree" class="form-label required">Durée:</label>
                                <input type="number" class="form-control" id="formation-duree" placeholder="Durée" name="duree" required>
                            <div class="text-danger" id="edit-duree-warning"></div>

                            </div>
                            <div class="col-md-6">
                                <label for="prix" class="form-label required">Prix:</label>
                                <input type="number" class="form-control" id="formation-prix" placeholder="Prix" name="prix" required>
                            <div class="text-danger" id="edit-prix-warning"></div>

                            </div>
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

        function validateForm(formId, warnings) {
        let isValid = true;
        for (let field in warnings) {
            const input = $(formId + ' #' + field);
            const warning = $(warnings[field]);
            if (input.val().trim() === '') {
                warning.text('Ce champ est requis.');
                isValid = false;
            } else {
                warning.text('');
            }
        }
        return isValid;
    }

        // Ajouter une formation
        $("#add-new-formation").click(function (e) {
            e.preventDefault();
            if (!validateForm('#formation-add-form', {
                    'new-formation-code': '#code-warning',
                    'new-formation-nom': '#nom-warning',
                    'new-formation-duree': '#duree-warning',
                    'new-formation-prix': '#prix-warning'
                })) {
                    return;
                }
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

        
    $('body').on('click', '#show-formation', function () {
        var id = $(this).data('id');

        $.ajax({
            url: '/formations/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                // Populate the formation details
                $('#formation-code-details').text(response.formation.code);
                $('#formation-nom-details').text(response.formation.nom);
                $('#formation-duree-details').text(response.formation.duree);
                $('#formation-prix-details').text(response.formation.prix);

                // Populate the contenus list
                var contenusList = $('#formation-contents-details');
                contenusList.empty();

                if (response.contenus && response.contenus.length > 0) {
                    response.contenus.forEach(function (contenu) {
                        contenusList.append('<li><strong>Chapitre</strong>: ' + contenu.nomchap + ', <strong>Unité</strong>: ' + contenu.nomunite + ', <br> <strong>Description</strong>:  ' + contenu.description + ' , <strong>Nombre des heures</strong>: ' + contenu.nombreheures + ' <br></li>');
                    });
                } else {
                    contenusList.append('<p>Aucun contenu trouvé pour cette formation.</p>');
                }

                // Show the modal
                $('#formationDetailModal').modal('show');
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                iziToast.error({
                    title: 'Erreur',
                    message: 'Une erreur s\'est produite: ' + errorMessage,
                    position: 'topRight'
                });
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
            if (!validateForm('#formation-edit-form', {
                    'formation-code': '#edit-code-warning',
                    'formation-nom': '#edit-nom-warning',
                    'formation-duree': '#edit-duree-warning',
                    'formation-prix': '#edit-prix-warning'
                })) {
                    return;
                }
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
