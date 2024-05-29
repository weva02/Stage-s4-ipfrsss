<!DOCTYPE html>
<html>
<head>
    <title>Laravel Ajax PUT Request Example</title>
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
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#contenueAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter un contenue
                            </button>
                            <a href="{{ route('export.contenues') }}" class="btn btn-success">Exporter Étudiants</a>
                        </div>
                        <form action="" method="get" class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" id="sear_bar" class="form-control" placeholder="Rechercher..." value="{{ isset($search) ? $search : ''}}">
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom du Chapitre</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom de l'unite</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nombre des Heures</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contenues as $contenue)
                                    <tr>
                                        <td>{{ $contenue->id }}</td>
                                        <td>{{ $contenue->numchap }}</td>
                                        <td>{{ $contenue->numunite }}</td>
                                        <td>{{ $contenue->description }}</td>
                                        <td>{{ $contenue->nombreheures }}</td>
                                        <td data-country-id="{{ $contenue->formation_id }}">{{ $contenue->formation->nom ?? 'N/A' }}</td>
                                        <td>
                                            <a href="javascript:void(0)" id="edit-contenue" data-id="{{ $contenue->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                                            <a href="{{ route('contenue.delete', $contenue->id) }}" id="delete-contenue" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $contenues->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Model modifier -->
  
    <!-- Edit Student Modal -->
<div class="modal fade" id="contenueEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier contenue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contenue-edit-form" enctype="multipart/form-data">
                    <input type="hidden" id="contenue-id" name="id">
                    <p><strong>Nom du Chapitre:</strong> <br /> <input type="text" name="numchap" id="contenue-numchap" class="form-control"></p>
                    <p><strong>Nom de L'unite:</strong> <br /> <input type="text" name="numunite" id="contenue-numunite" class="form-control"></p>
                    
                    <p><strong>Description:</strong> <br /> <input type="text" name="description" id="contenue-description" class="form-control"></p>
                    <p><strong>NombreHeures:</strong> <br /> <input type="text" name="nombreheures" id="contenue-nombreheures" class="form-control"></p>
                    <div class="form-group">
                        <label for="formation_id">Formation</label>
                        <select class="form-control" id="contenue-formation_id" name="formation_id">
                            <option value="">Select Formation</option>
                            @foreach ($formations as $formation)
                                <option value="{{ $formation->id }}">{{ $formation->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="contenue-update">Modifier</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

    <!-- Ajouter un prof Modal -->
    <!-- Ajouter un étudiant Modal -->
<div class="modal fade" id="contenueAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouvel contenue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contenue-add-form" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="numchap" class="form-label">Nom du Chapitre:</label>
                        <input type="text" class="form-control" id="new-contenue-numchap" name="numchap">
                    </div>
                    <div class="mb-3">
                        <label for="numunite" class="form-label">Nom de l'unite:</label>
                        <input type="text" class="form-control" id="new-contenue-numunite" name="numunite">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <input type="text" class="form-control" id="new-contenue-description" name="description">
                    </div>
                    <div class="mb-3">
                        <label for="nombreheures" class="form-label">NombreHeures:</label>
                        <input type="text" class="form-control" id="new-contenue-nombreheures" name="nombreheures">
                    </div>
                    <div class="form-group">
                        <label for="formation_id">Formation</label>
                        <select class="form-control" id="new-contenue-formation_id" name="formation_id">
                            <option value="">Select Formation</option>
                            @foreach ($formations as $formation)
                                <option value="{{ $formation->id }}">{{ $formation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="add-new-contenue">Ajouter</button>
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

    $("#add-new-contenue").click(function(e){
        e.preventDefault();
        let form = $('#contenue-add-form')[0];
        let data = new FormData(form);

        $.ajax({
            url: "{{ route('contenue.store') }}",
            type: "POST",
            data: data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.errors) {
                    var errorMsg = '';
                    $.each(response.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            errorMsg += error + '<br>';
                        });
                    });
                    iziToast.error({
                        message: errorMsg,
                        position: 'topRight'
                    });
                } else {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                    $('#contenueAddModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errorMsg = '';
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            errorMsg += error + '<br>';
                        });
                    });
                    iziToast.error({
                        message: errorMsg,
                        position: 'topRight'
                    });
                } else {
                    iziToast.error({
                        message: 'An error occurred: ' + error,
                        position: 'topRight'
                    });
                }
            }
        });
    });

    $('#image').change(function (){
        const file = this.files[0];
        if(file){
            let reader = new FileReader();
            reader.onload = function (event){
                $('#imagePreview').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $('body').on('click', '#edit-contenue', function () {
        var tr = $(this).closest('tr');
        $('#contenue-id').val($(this).data('id'));
        $('#contenue-numchap').val(tr.find("td:nth-child(2)").text());
        $('#contenue-numunite').val(tr.find("td:nth-child(3)").text());
        $('#contenue-description').val(tr.find("td:nth-child(4)").text());
        $('#contenue-nombreheures').val(tr.find("td:nth-child(5)").text());
        $('#contenue-formation_id').val(tr.find("td:nth-child(6)").data('formation-id'));


        $('#contenueEditModal').modal('show');
    });

    $('body').on('click', '#contenue-update', function () {
        var id = $('#contenue-id').val();
        var formData = new FormData($('#contenue-edit-form')[0]);
        formData.append('_method', 'PUT');

        $.ajax({
            url: "{{ route('contenue.update', '') }}/" + id,
            type: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#econtenueEditModal').modal('hide');
                if (response.success) {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                } else {
                    iziToast.error({
                        message: response.error,
                        position: 'topRight'
                    });
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                var errorMsg = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            errorMsg += error + '<br>';
                        });
                    });
                } else {
                    errorMsg = 'An error occurred: ' + error;
                }
                iziToast.error({
                    message: errorMsg,
                    position: 'topRight'
                });
            }
        });
    });

    $('body').on('click', '#delete-contenue', function (e) {
        e.preventDefault();
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet étudiant ?");
        if (confirmation) {
            $.ajax({
                url: $(this).attr('href'),
                type: 'DELETE',
                success: function(response) {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                    location.reload();
                },
                error: function(xhr, status, error) {
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
        setTimeout(function() {
            alertElement.style.display = 'none';
        }, 2000);
    }
});
    </script>
</body>
</html>