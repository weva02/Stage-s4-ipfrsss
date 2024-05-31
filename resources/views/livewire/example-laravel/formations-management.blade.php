<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Formations Management</title>
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
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter une Formation
                            </button>
                            <a href="{{ route('formations.export') }}" class="btn btn-success">Exporter Formations</a>
                        </div>
                        <form action="/search" method="get" class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" id="search_bar" class="form-control" placeholder="Rechercher..." value="{{ isset($search) ? $search : ''}}">
                                <button type="submit" class="btn btn-primary">Rechercher</button>
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

    <!-- Modal Détails Formation -->
    <!-- <div class="modal fade" id="formationDetailModal" tabindex="-1" aria-labelledby="formationDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formationDetailModalLabel">Détails de la Formation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="formation-details">
                        <h1>Détails de la Formation</h1>
                        <p><strong>Code:</strong> <span id="formation-code-details"></span></p>
                        <p><strong>Nom:</strong> <span id="formation-nom-details"></span></p>
                        <p><strong>Durée:</strong> <span id="formation-duree-details"></span> heures</p>
                        <p><strong>Prix:</strong> <span id="formation-prix-details"></span> $</p>
                        
                        <h2>Contenus de la Formation</h2>
                        <ul id="formation-contents-details"></ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Modal Ajouter Formation -->
    <!-- <div class="modal fade" id="formationAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    </div> -->

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
                            <input type="text" class="form-control" id="new-formation-code" name="code">
                        </div>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom :</label>
                            <input type="text" class="form-control" id="new-formation-nom" name="nom">
                        </div>
                        <div class="mb-3">
                            <label for="duree" class="form-label">Durée:</label>
                            <input type="number" class="form-control" id="new-formation-duree" name="duree">
                        </div>
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix:</label>
                            <input type="number" class="form-control" id="new-formation-prix" name="prix">
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
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="formationDetailModalLabel">Détails de la Formation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="formation-details">
                    <p><strong>Code:</strong> <span id="formation-code-details"></span></p>
                    <p><strong>Nom:</strong> <span id="formation-nom-details"></span></p>
                    <p><strong>Durée:</strong> <span id="formation-duree-details"></span> Heures</p>
                    <p><strong>Prix:</strong> <span id="formation-prix-details"></span> MRU</p>
                    
                    <h4>Contenus de la Formation</h4>
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

        // Afficher les détails de la formation
        // $('body').on('click', '#show-formation', function () {
        //     var id = $(this).data('id');

        //     $.ajax({
        //         url: '/formations/' + id,
        //         type: 'GET',
        //         dataType: 'json',
        //         success: function (response) {
        //             $('#formation-code-details').text(response.formation.code);
        //             $('#formation-nom-details').text(response.formation.nom);
        //             $('#formation-duree-details').text(response.formation.duree);
        //             $('#formation-prix-details').text(response.formation.prix);

        //             var contenusList = $('#formation-contents-details');
        //             contenusList.empty();

        //             if (response.contenus.length > 0) {
        //                 response.contenus.forEach(function (contenu) {
        //                     contenusList.append('<li><strong>Chapitre ' + contenu.NumChap + ', Unité ' + contenu.NumUnite + '</strong>: ' + contenu.description + ' (' + contenu.NombreHeures + ' heures)</li>');
        //                 });
        //             } else {
        //                 contenusList.append('<p>Aucun contenu trouvé pour cette formation.</p>');
        //             }

        //             $('#formationDetailModal').modal('show');
        //         },
        //         error: function (xhr, status, error) {
        //             iziToast.error({
        //                 title: 'Erreur',
        //                 message: 'Une erreur s\'est produite: ' + error,
        //                 position: 'topRight'
        //             });
        //         }
        //     });
        // });

        
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

    <!-- <script type="text/javascript">
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

            // Afficher les détails de la formation
            $('body').on('click', '#formation-show', function () {
                var id = $(this).data('id');

                $.ajax({
                    url: '/formations/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        $('#formation-code-details').text(response.formation.code);
                        $('#formation-nom-details').text(response.formation.nom);
                        $('#formation-duree-details').text(response.formation.duree);
                        $('#formation-prix-details').text(response.formation.prix);

                        var contenusList = $('#formation-contents-details');
                        contenusList.empty();

                        if (response.contenus.length > 0) {
                            response.contenus.forEach(function (contenu) {
                                contenusList.append('<li><strong>Chapitre ' + contenu.NumChap + ', Unité ' + contenu.NumUnite + '</strong>: ' + contenu.description + ' (' + contenu.NombreHeures + ' heures)</li>');
                            });
                        } else {
                            contenusList.append('<p>Aucun contenu trouvé pour cette formation.</p>');
                        }

                        // $('#formationDetailModal').modal('show');
                        $('#formationDetailModal').modal('hide');

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
    </script> -->
</body>
</html>
