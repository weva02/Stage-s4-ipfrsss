<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Étudiants</title>
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
                        <form action="/search" method="get" class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" id="search_bar" class="form-control" placeholder="Rechercher..." value="{{ isset($search) ? $search : ''}}">
                                <button type="submit" class="btn btn-primary">Rechercher</button>
                            </div>
                        </form>
                    </div>

                    <div class="me-3 my-3 text-end"></div>

                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NNI</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom & Prenom</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nationalité</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Diplome</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Genre</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lieu Naissance</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Adresse</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Age</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
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
                                            <a href="javascript:void(0)" id="edit-etudiant" class="btn btn-info" data-url="{{ route('etudiant.update', $etudiant->id) }}"><i class="material-icons opacity-10">border_color</i></a>
                                            <a href="/delete-etudiant/{{ $etudiant->id }}" id="delete-etudiant" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
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
                        <div class="row mb-3"></div>
                        <p><strong>NNI <span class="required"></span>:</strong><br/><input type="text" name="nni" id="etudiant-nni" class="form-control" required></span></p>
                        <p><strong>Nom & Prenom <span class="required"></span>:</strong><br/><input type="text" name="nomprenom" id="etudiant-nomprenom" class="form-control" required></span></p>
                        <p><strong>Nationalité <span class="required"></span>:</strong><br/>
                            <select class="form-control" id="etudiant-nationalite" name="nationalite" required>
                                @foreach($countries as $country)
                                    <option value="{{ $country->name }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </p>
                        <p><strong>Diplome:</strong><br/><input type="text" name="diplome" id="etudiant-diplome" class="form-control"></span></p>
                        <p><strong>Genre:</strong><br/>
                            <label><input type="radio" name="genre" value="Homme" id="etudiant-genre-homme"> Homme</label>
                            <label><input type="radio" name="genre" value="Femme" id="etudiant-genre-femme"> Femme</label>
                        </p>
                        <p><strong>Lieu Naissance:</strong><br/><input type="text" name="lieunaissance" id="etudiant-lieunaissance" class="form-control"></span></p>
                        <p><strong>Adresse:</strong><br/><input type="text" name="adress" id="etudiant-adress" class="form-control"></span></p>
                        <p><strong>Age:</strong><br/><input type="text" name="age" id="etudiant-age" class="form-control"></span></p>
                        <p><strong>Email:</strong><br/><input type="email" name="email" id="etudiant-email" class="form-control"></span></p>
                        <p><strong>Portable <span class="required"></span>:</strong><br/><input type="text" name="phone" id="etudiant-phone" class="form-control" required></span></p>
                        <p><strong>WhatsApp:</strong><br/><input type="text" name="wtsp" id="etudiant-wtsp" class="form-control"></span></p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="etudiant-update">Modifier</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ajouter un étudiant Modal -->
    <div class="modal fade" id="etudiantAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouvel étudiant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="etudiant-add-form">
                        @csrf
                        <div class="mb-3">
                            <label for="image" class="form-label">Image:</label>
                            <input type="file" class="form-control" id="new-etudiant-image" name="image">
                        </div>
                        <div class="mb-3">
                            <label for="nni" class="form-label required">NNI:</label>
                            <input type="text" class="form-control" id="new-etudiant-nni" name="nni" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomprenom" class="form-label required">Nom & Prenom:</label>
                            <input type="text" class="form-control" id="new-etudiant-nomprenom" name="nomprenom" required>
                        </div>
                        <div class="mb-3">
                            <label for="nationalite" class="form-label required">Nationalité:</label>
                            <select class="form-control" id="new-etudiant-nationalite" name="nationalite" required>
                                @foreach($countries as $country)
                                    <option value="{{ $country->name }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="diplome" class="form-label">Diplome:</label>
                            <input type="text" class="form-control" id="new-etudiant-diplome" name="diplome">
                        </div>
                        <p><strong>Genre:</strong><br/>
                            <label><input type="radio" name="genre" value="Homme" id="new-prof-genre-homme"> Homme</label>
                            <br>
                            <label><input type="radio" name="genre" value="Femme" id="new-prof-genre-femme"> Femme</label>
                        </p>
                        <div class="mb-3">
                            <label for="lieunaissance" class="form-label">Lieu Naissance:</label>
                            <input type="text" class="form-control" id="new-etudiant-lieunaissance" name="lieunaissance">
                        </div>
                        <div class="mb-3">
                            <label for="adress" class="form-label">Adresse:</label>
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
                            <label for="phone" class="form-label required">Portable:</label>
                            <input type="text" class="form-control" id="new-etudiant-phone" name="phone" required>
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

            // Fonction de soumission du formulaire pour ajouter un nouvel étudiant
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
            })

            // Edit student
            $('body').on('click', '#edit-etudiant', function () {
                var etudiantURL = $(this).data('url');

                $('#etudiantEditModal').modal('show');
                var tr = $(this).closest('tr');
                $('#etudiant-id').val(tr.find("td:nth-child(1)").text());
                $('#etudiant-image').val(tr.find("td:nth-child(2)").text());
                $('#etudiant-nni').val(tr.find("td:nth-child(3)").text());
                $('#etudiant-nomprenom').val(tr.find("td:nth-child(4)").text());
                $('#etudiant-nationalite').val(tr.find("td:nth-child(5)").text());
                $('#etudiant-diplome').val(tr.find("td:nth-child(6)").text());
                $('#etudiant-genre').val(tr.find("td:nth-child(7)").text());
                $('#etudiant-lieunaissance').val(tr.find("td:nth-child(8)").text());
                $('#etudiant-adress').val(tr.find("td:nth-child(9)").text());
                $('#etudiant-age').val(tr.find("td:nth-child(10)").text());
                $('#etudiant-email').val(tr.find("td:nth-child(11)").text());
                $('#etudiant-phone').val(tr.find("td:nth-child(12)").text());
                $('#etudiant-wtsp').val(tr.find("td:nth-child(13)").text());

                // Pré-sélection du genre
                var genre = tr.find("td:nth-child(7)").text();
                if(genre === "Homme") {
                    $('#etudiant-genre-homme').prop('checked', true);
                } else if(genre === "Femme") {
                    $('#etudiant-genre-femme').prop('checked', true);
                }
            });

            // Update student
            $('body').on('click', '#etudiant-update', function () {
                var id = $('#etudiant-id').val();
                var formData = new FormData($('#etudiant-edit-form')[0]);

                $.ajax({
                    url: '/etudiants/' + id,
                    type: 'PUT',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#etudiantEditModal').modal('hide');
                        if (response.success) {
                            iziToast.success({
                                message: response.success,
                                position: 'topRight',
                            });
                            location.reload();
                        } else {
                            iziToast.error({
                                message: response.error,
                                position: 'topRight',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        iziToast.error({
                            message: 'Une erreur s\'est produite : ' + error,
                            position: 'topRight'
                        });
                    }
                });
            });

            $('body').on('click', '#delete-etudiant', function (e) {
                e.preventDefault();
                var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet étudiant ?");
                if (confirmation) {
                    window.location.href = $(this).attr('href');
                }
            });

            // Fade out alert
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
