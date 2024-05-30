<!DOCTYPE html>
<html>
<head>
    <title>Laravel Ajax CRUD Example</title>
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
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#profAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter un Professeur
                            </button>
                            <a href="{{ route('export.profs') }}" class="btn btn-success">Exporter Professeurs</a>
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom & Prenom</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nationalité</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Diplome</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Portable</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">WhatsApp</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type Contrat</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($profs as $prof)
                                    <tr>
                                        <td>{{ $prof->id }}</td>
                                        <td><img src="{{ asset('images/'.$prof->image) }}" alt="" width="60px"></td>
                                        <td>{{ $prof->nomprenom }}</td>
                                        <td data-country-id="{{ $prof->country_id }}">{{ $prof->country->name ?? 'N/A' }}</td>
                                        <td>{{ $prof->email }}</td>
                                        <td>{{ $prof->diplome }}</td>
                                        <td>{{ $prof->phone }}</td>
                                        <td>{{ $prof->wtsp }}</td>
                                        <td data-typeymntprof-id="{{ $prof->typeymntprof_id }}">{{ $prof->typeymntprof->type ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" id="edit-prof" data-id="{{ $prof->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                                            <a href="javascript:void(0)" id="delete-prof" data-id="{{ $prof->id }}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $profs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ajouter un Prof Modal -->
    <div class="modal fade" id="profAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouvel Professeur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="prof-add-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="image" class="form-label">Image:</label>
                                <input type="file" class="form-control" id="new-prof-image" name="image">
                            </div>
                            <div class="col-md-6">
                                <label for="nomprenom" class="form-label">Nom & Prenom:</label>
                                <input type="text" class="form-control" id="new-prof-nomprenom" name="nomprenom">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="country_id" class="form-label">Country</label>
                                <select class="form-control" id="new-prof-country_id" name="country_id">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="new-prof-email" name="email">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="diplome" class="form-label">Diplome:</label>
                                <input type="text" class="form-control" id="new-prof-diplome" name="diplome">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Portable:</label>
                                <input type="text" class="form-control" id="new-prof-phone" name="phone">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="wtsp" class="form-label">WhatsApp:</label>
                                <input type="text" class="form-control" id="new-prof-wtsp" name="wtsp">
                            </div>
                            <div class="col-md-6">
                                <label for="typeymntprof_id" class="form-label">Type Contrat</label>
                                <select class="form-control" id="new-prof-typeymntprof_id" name="typeymntprof_id">
                                    <option value="">Select Type Contrat</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="add-new-prof">Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Prof Modal -->
    <div class="modal fade" id="profEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier Professeur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="prof-edit-form" enctype="multipart/form-data">
                        <input type="hidden" id="prof-id" name="id">
                        <div class="row mb-3">
                            <label for="inputImage" class="col-sm-2 col-form-label">Image</label>
                            <div class="col-sm-10">
                                <img src="" id="imagePreview" class="imgUpload" alt="">
                                <input type="file" class="form-control" name="image" id="image">
                            </div>
                        </div>
                        <p><strong>Nom & Prenom:</strong> <br /> <input type="text" name="nomprenom" id="prof-nomprenom" class="form-control"></p>
                        <div class="form-group">
                            <label for="country_id">Country</label>
                            <select class="form-control" id="prof-country_id" name="country_id">
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p><strong>Email:</strong> <br /> <input type="email" name="email" id="prof-email" class="form-control"></p>
                        <p><strong>Diplome:</strong> <br /> <input type="text" name="diplome" id="prof-diplome" class="form-control"></p>
                        <p><strong>Portable:</strong> <br /> <input type="text" name="phone" id="prof-phone" class="form-control"></p>
                        <p><strong>WhatsApp:</strong> <br /> <input type="text" name="wtsp" id="prof-wtsp" class="form-control"></p>
                        <div class="form-group">
                            <label for="typeymntprof_id">Type Contrat</label>
                            <select class="form-control" id="prof-typeymntprof_id" name="typeymntprof_id">
                                <option value="">Select Type</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="prof-update">Modifier</button>
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

            $("#add-new-prof").click(function(e){
                e.preventDefault();
                let form = $('#prof-add-form')[0];
                let data = new FormData(form);

                $.ajax({
                    url: "{{ route('prof.store') }}",
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
                            $('#profAddModal').modal('hide');
                            // Ajouter le nouvel enregistrement à la table sans recharger la page
                            const prof = response.prof;
                            const row = `
                                <tr>
                                    <td>${prof.id}</td>
                                    <td><img src="{{ asset('images') }}/${prof.image}" alt="" width="60px"></td>
                                    <td>${prof.nomprenom}</td>
                                    <td data-country-id="${prof.country_id}">${prof.country.name}</td>
                                    <td>${prof.email}</td>
                                    <td>${prof.diplome}</td>
                                    <td>${prof.phone}</td>
                                    <td>${prof.wtsp}</td>
                                    <td data-typeymntprof-id="${prof.typeymntprof_id}">${prof.typeymntprof.type}</td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" id="edit-prof" data-id="${prof.id}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                                        <a href="javascript:void(0)" id="delete-prof" data-id="${prof.id}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                                    </td>
                                </tr>
                            `;
                            $('table tbody').prepend(row);
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

            $('body').on('click', '#edit-prof', function () {
                var tr = $(this).closest('tr');
                $('#prof-id').val($(this).data('id'));
                $('#prof-nomprenom').val(tr.find("td:nth-child(3)").text());
                $('#prof-country_id').val(tr.find("td:nth-child(4)").data('country-id'));
                $('#prof-email').val(tr.find("td:nth-child(5)").text());
                $('#prof-diplome').val(tr.find("td:nth-child(6)").text());
                $('#prof-phone').val(tr.find("td:nth-child(7)").text());
                $('#prof-wtsp').val(tr.find("td:nth-child(8)").text());
                $('#prof-typeymntprof_id').val(tr.find("td:nth-child(9)").data('typeymntprof-id'));

                $('#imagePreview').attr('src', tr.find("td:nth-child(2) img").attr('src'));

                $('#profEditModal').modal('show');
            });

            // $('body').on('click', '#prof-update', function () {
            //     var id = $('#prof-id').val();
            //     var formData = new FormData($('#prof-edit-form')[0]);
            //     formData.append('_method', 'PUT');

            //     $.ajax({
            //         url: "{{ route('prof.update', '') }}/" + id,
            //         type: 'POST',
            //         dataType: 'json',
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         success: function(response) {
            //             $('#profEditModal').modal('hide');
            //             if (response.success) {
            //                 iziToast.success({
            //                     message: response.success,
            //                     position: 'topRight'
            //                 });
            //                 // Mettre à jour la ligne dans le tableau sans recharger la page
            //                 const prof = response.prof;
            //                 const tr = $(a[data-id='${id}']).closest('tr');
            //                 tr.find("td:nth-child(2) img").attr('src', {{ asset('images') }}/${prof.image});
            //                 tr.find("td:nth-child(3)").text(prof.nomprenom);
            //                 tr.find("td:nth-child(4)").text(prof.country.name).attr('data-country-id', prof.country_id);
            //                 tr.find("td:nth-child(5)").text(prof.email);
            //                 tr.find("td:nth-child(6)").text(prof.diplome);
            //                 tr.find("td:nth-child(7)").text(prof.phone);
            //                 tr.find("td:nth-child(8)").text(prof.wtsp);
            //                 tr.find("td:nth-child(9)").text(prof.typeymntprof.type).attr('data-typeymntprof-id', prof.typeymntprof_id);
            //             } else {
            //                 iziToast.error({
            //                     message: response.error,
            //                     position: 'topRight'
            //                 });
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             var errorMsg = '';
            //             if (xhr.responseJSON && xhr.responseJSON.errors) {
            //                 $.each(xhr.responseJSON.errors, function(field, errors) {
            //                     $.each(errors, function(index, error) {
            //                         errorMsg += error + '<br>';
            //                     });
            //                 });
            //             } else {
            //                 errorMsg = 'An error occurred: ' + error;
            //             }
            //             iziToast.error({
            //                 message: errorMsg,
            //                 position: 'topRight'
            //             });
            //         }
            //     });
            // });

            // $('body').on('click', '#delete-prof', function (e) {
            //     e.preventDefault();
            //     var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce professeur ?");
            //     if (confirmation) {
            //         var id = $(this).data('id');
            //         $.ajax({
            //             url: "{{ route('prof.delete', '') }}/" + id,
            //             type: 'DELETE',
            //             success: function(response) {
            //                 iziToast.success({
            //                     message: response.success,
            //                     position: 'topRight'
            //                 });
            //                 // Supprimer la ligne du tableau sans recharger la page
            //                 $(a[data-id='${id}']).closest('tr').remove();
            //             },
            //             error: function(xhr, status, error) {
            //                 iziToast.error({
            //                     message: 'An error occurred: ' + error,
            //                     position: 'topRight'
            //                 });
            //             }
            //         });
            //     }
            // });

            $('body').on('click', '#prof-update', function () {
    var id = $('#prof-id').val();
    var formData = new FormData($('#prof-edit-form')[0]);
    formData.append('_method', 'PUT');

    $.ajax({
        url: "{{ route('prof.update', '') }}/" + id,
        type: 'POST',
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#profEditModal').modal('hide');
            if (response.success) {
                iziToast.success({
                    message: response.success,
                    position: 'topRight'
                });
                // Update the row in the table without reloading the page
                const prof = response.prof;
                const tr = $("a[data-id='" + id + "']").closest('tr');
                tr.find("td:nth-child(2) img").attr('src', "{{ asset('images') }}/" + prof.image);
                tr.find("td:nth-child(3)").text(prof.nomprenom);
                tr.find("td:nth-child(4)").text(prof.country.name).attr('data-country-id', prof.country_id);
                tr.find("td:nth-child(5)").text(prof.email);
                tr.find("td:nth-child(6)").text(prof.diplome);
                tr.find("td:nth-child(7)").text(prof.phone);
                tr.find("td:nth-child(8)").text(prof.wtsp);
                tr.find("td:nth-child(9)").text(prof.typeymntprof.type).attr('data-typeymntprof-id', prof.typeymntprof_id);
            } else {
                iziToast.error({
                    message: response.error,
                    position: 'topRight'
                });
            }
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

$('body').on('click', '#delete-prof', function (e) {
    e.preventDefault();
    var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce professeur ?");
    if (confirmation) {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('prof.delete', '') }}/" + id,
            type: 'DELETE',
            success: function(response) {
                iziToast.success({
                    message: response.success,
                    position: 'topRight'
                });
                // Remove the row from the table without reloading the page
                $("a[data-id='" + id + "']").closest('tr').remove();
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