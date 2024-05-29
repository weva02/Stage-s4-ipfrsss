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
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#etudiantAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter un Etudiant
                            </button>
                            <a href="{{ route('export.etudiants') }}" class="btn btn-success">Exporter Étudiants</a>
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NNI</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom & Prenom</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nationalite</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Diplome</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Genre</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lieu Naissance</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Address</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Age</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">EMAIL</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Portable</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">WhatsApp</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etudiants as $etudiant)
                                    <tr>
                                        <td>{{ $etudiant->id }}</td>
                                        <td><img src="{{ asset('images/'.$etudiant->image)}}" alt="" width="60px"></td>
                                        <td>{{ $etudiant->nni }}</td>
                                        <td>{{ $etudiant->nomprenom }}</td>
                                        <td data-country-id="{{ $etudiant->country_id }}">{{ $etudiant->country->name ?? 'N/A' }}</td>
                                        <td>{{ $etudiant->diplome }}</td>
                                        <td>{{ $etudiant->genre }}</td>
                                        <td>{{ $etudiant->lieunaissance }}</td>
                                        <td>{{ $etudiant->adress }}</td>
                                        <td>{{ $etudiant->age }}</td>
                                        <td>{{ $etudiant->email }}</td>
                                        <td>{{ $etudiant->phone }}</td>
                                        <td>{{ $etudiant->wtsp }}</td>
                                        <td>
                                            <a href="javascript:void(0)" id="edit-etudiant" data-id="{{ $etudiant->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                                            <a href="{{ route('etudiant.delete', $etudiant->id) }}" id="delete-etudiant" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $etudiants->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Model modifier -->
  
    <!-- Edit Student Modal -->
<div class="modal fade" id="etudiantEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier Etudiant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="etudiant-edit-form" enctype="multipart/form-data">
                    <input type="hidden" id="etudiant-id" name="id">
                    <div class="row mb-3">
                        <label for="inputImage" class="col-sm-2 col-form-label">Image</label>
                        <div class="col-sm-10">
                            <img src="" id="imagePreview" class="imgUpload" alt="">
                            <input type="file" class="form-control" name="image" id="image">
                        </div>
                    </div>
                    <p><strong>NNI:</strong> <br /> <input type="text" name="nni" id="etudiant-nni" class="form-control"></p>
                    <p><strong>Nom & Prenom:</strong> <br /> <input type="text" name="nomprenom" id="etudiant-nomprenom" class="form-control"></p>
                    <div class="form-group">
                        <label for="country_id">Country</label>
                        <select class="form-control" id="etudiant-country_id" name="country_id">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p><strong>Diplome:</strong> <br /> <input type="text" name="diplome" id="etudiant-diplome" class="form-control"></p>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre:</label>
                        <div>
                            <input type="radio" id="male" name="genre" value="Male">
                            <label for="male">Male</label><br>
                            <input type="radio" id="female" name="genre" value="Female">
                            <label for="female">Female</label><br>
                        </div>
                    </div>
                    <p><strong>Lieu Naissance:</strong> <br /> <input type="text" name="lieunaissance" id="etudiant-lieunaissance" class="form-control"></p>
                    <p><strong>Address:</strong> <br /> <input type="text" name="adress" id="etudiant-adress" class="form-control"></p>
                    <p><strong>Age:</strong> <br /> <input type="text" name="age" id="etudiant-age" class="form-control"></p>
                    <p><strong>Email:</strong> <br /> <input type="email" name="email" id="etudiant-email" class="form-control"></p>
                    <p><strong>Portable:</strong> <br /> <input type="text" name="phone" id="etudiant-phone" class="form-control"></p>
                    <p><strong>WhatsApp:</strong> <br /> <input type="text" name="wtsp" id="etudiant-wtsp" class="form-control"></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="etudiant-update">Modifier</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

    <!-- Ajouter un prof Modal -->
    <!-- Ajouter un étudiant Modal -->
<div class="modal fade" id="etudiantAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouvel étudiant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="etudiant-add-form" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="image" class="form-label">Image:</label>
                        <input type="file" class="form-control" id="new-etudiant-image" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="nni" class="form-label">NNI:</label>
                        <input type="text" class="form-control" id="new-etudiant-nni" name="nni">
                    </div>
                    <div class="mb-3">
                        <label for="nomprenom" class="form-label">Nom & Prenom:</label>
                        <input type="text" class="form-control" id="new-etudiant-nomprenom" name="nomprenom">
                    </div>
                    <div class="form-group">
                        <label for="country_id">Country</label>
                        <select class="form-control" id="new-etudiant-country_id" name="country_id">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="diplome" class="form-label">Diplome:</label>
                        <input type="text" class="form-control" id="new-etudiant-diplome" name="diplome">
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre:</label>
                        <div>
                            <input type="radio" id="male" name="genre" value="Male">
                            <label for="male">Male</label><br>
                            <input type="radio" id="female" name="genre" value="Female">
                            <label for="female">Female</label><br>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="lieunaissance" class="form-label">Lieu Naissance:</label>
                        <input type="text" class="form-control" id="new-etudiant-lieunaissance" name="lieunaissance">
                    </div>
                    <div class="mb-3">
                        <label for="adress" class="form-label">Address:</label>
                        <input type="text" class="form-control" id="new-etudiant-adress" name="adress">
                    </div>
                    <div class="mb-3">
                        <label for="age" class="form-label">Age:</label>
                        <input type="text" class="form-control" id="new-etudiant-age" name="age">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="new-etudiant-email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Portable:</label>
                        <input type="text" class="form-control" id="new-etudiant-phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="wtsp" class="form-label">WhatsApp:</label>
                        <input type="text" class="form-control" id="new-etudiant-wtsp" name="wtsp">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="add-new-etudiant">Ajouter</button>
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

    $("#add-new-etudiant").click(function(e){
        e.preventDefault();
        let form = $('#etudiant-add-form')[0];
        let data = new FormData(form);

        $.ajax({
            url: "{{ route('etudiant.store') }}",
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
                    $('#etudiantAddModal').modal('hide');
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

    $('body').on('click', '#edit-etudiant', function () {
        var tr = $(this).closest('tr');
        $('#etudiant-id').val($(this).data('id'));
        $('#etudiant-nni').val(tr.find("td:nth-child(3)").text());
        $('#etudiant-nomprenom').val(tr.find("td:nth-child(4)").text());
        $('#etudiant-country_id').val(tr.find("td:nth-child(5)").data('country-id'));
        $('#etudiant-diplome').val(tr.find("td:nth-child(6)").text());
        var genre = tr.find("td:nth-child(7)").text();
        $('input[name="genre"][value="' + genre + '"]').prop('checked', true);
        $('#etudiant-lieunaissance').val(tr.find("td:nth-child(8)").text());
        $('#etudiant-adress').val(tr.find("td:nth-child(9)").text());
        $('#etudiant-age').val(tr.find("td:nth-child(10)").text());
        $('#etudiant-email').val(tr.find("td:nth-child(11)").text());
        $('#etudiant-phone').val(tr.find("td:nth-child(12)").text());
        $('#etudiant-wtsp').val(tr.find("td:nth-child(13)").text());
        $('#imagePreview').attr('src', tr.find("td:nth-child(2) img").attr('src'));

        $('#etudiantEditModal').modal('show');
    });

    $('body').on('click', '#etudiant-update', function () {
        var id = $('#etudiant-id').val();
        var formData = new FormData($('#etudiant-edit-form')[0]);
        formData.append('_method', 'PUT');

        $.ajax({
            url: "{{ route('etudiant.update', '') }}/" + id,
            type: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#etudiantEditModal').modal('hide');
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

    $('body').on('click', '#delete-etudiant', function (e) {
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
