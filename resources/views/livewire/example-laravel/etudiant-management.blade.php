<!DOCTYPE html>
<html>
<head>
    <title>Laravel Ajax PUT Request Example  - ItSolutionStuff.com</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Inclure le CSS d'IziToast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

    <!-- Inclure jQuery -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->

    <!-- Inclure le script d'IziToast -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
        <!-- Navbar -->
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    @if (session('status'))
                    <div class="alert alert-success fade-out">
                        {{ session('status')}}
                    </div>
                    @endif
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            
                        </div>
                        <div class="me-3 my-3 text-end">
                    <button type="button" class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#etudiantAddModal"><i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter un Apprenant</button>
                </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                ID
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                NNI
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nom & Prenom</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Nationalite</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Diplome</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Genre</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Lieu Naissance</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Address</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Age</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                EMAIL</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Portable</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                WhatsApp</th>
                            
                                            <!-- <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                CREATION DATE
                                            </th> -->
                                            <th class="text-secondary opacity-7"></th>
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
                                            <a href="javascript:void(0)" id="edit-etudiant" class="btn btn-info">Modifier</a>
                                            <a href="/delete-etudiant/{{ $etudiant->id }}" id="delete-etudiant" class="btn btn-danger">Supprimer</a>
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
        <input type="hidden" id="etudiant-id" name="id"></span>
        <p><strong>NNI:</strong> <br/> <input type="text" name="nni" id="etudiant-nni" class="form-control"></span></p>
        <p><strong>Nom & Prenom:</strong> <br/> <input type="text" name="nomprenom" id="etudiant-nomprenom" class="form-control"></span></p>
        <p><strong>Nationalite:</strong> <br/> <input type="text" name="nationalite" id="etudiant-nationalite" class="form-control"></span></p>
        <p><strong>Diplome:</strong> <br/> <input type="text" name="diplome" id="etudiant-diplome" class="form-control"></span></p>
        <p><strong>Genre:</strong> <br/> <input type="text" name="genre" id="etudiant-genre" class="form-control"></span></p>
        <p><strong>Lieu Naissance:</strong> <br/> <input type="text" name="lieunaissance" id="etudiant-lieunaissance" class="form-control"></span></p>
        <p><strong>Address:</strong> <br/> <input type="text" name="adress" id="etudiant-adress" class="form-control"></span></p>
        <p><strong>Age:</strong> <br/> <input type="text" name="age" id="etudiant-age" class="form-control"></span></p>
        <p><strong>Email:</strong> <br/> <input type="email" name="email" id="etudiant-email" class="form-control"></span></p>
        <p><strong>Portable:</strong> <br/> <input type="text" name="phone" id="etudiant-phone" class="form-control"></span></p>
        <p><strong>WhatsApp:</strong> <br/> <input type="text" name="wtsp" id="etudiant-wtsp" class="form-control"></span></p>
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-info" id="etudiant-update">Modifier</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Model ajouter -->

<!-- Ajouter un étudiant Modal -->
<!-- <div class="modal fade" id="etudiantAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <label for="nni" class="form-label">NNI:</label>
                        <input type="text" class="form-control" id="new-etudiant-nni" name="nni">
                    </div>
                    <div class="mb-3">
                        <label for="nni" class="form-label">Nom & Prenom:</label>
                        <input type="text" class="form-control" id="new-etudiant-nomprenom" name="nomprenom">
                    </div><div class="mb-3">
                        <label for="nationalite" class="form-label">Nationalite:</label>
                        <input type="text" class="form-control" id="new-etudiant-nationalite" name="nationalite">
                    </div><div class="mb-3">
                        <label for="diplome" class="form-label">Diplome:</label>
                        <input type="text" class="form-control" id="new-etudiant-diplome" name="diplome">
                    </div><div class="mb-3">
                        <label for="genre" class="form-label">Genre:</label>
                        <input type="text" class="form-control" id="new-etudiant-genre" name="genre">
                    </div><div class="mb-3">
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
                        <label for="wtsp" class="form-label">WhatsApp:</label>
                        <input type="text" class="form-control" id="new-etudiant-wtsp" name="wtsp">
                    </div>
            </div>
            <div class="modal-footer">
            
                <button type="button" class="btn btn-info" id="add-new-etudiant">Ajouter</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div> -->

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
        <label for="nni" class="form-label">NNI:</label>
        <input type="text" class="form-control" id="new-etudiant-nni" name="nni">
    </div>
    <div class="mb-3">
        <label for="nomprenom" class="form-label">Nom & Prenom:</label>
        <input type="text" class="form-control" id="new-etudiant-nomprenom" name="nomprenom">
    </div>
    <div class="mb-3">
        <label for="nationalite" class="form-label">Nationalite:</label>
        <input type="text" class="form-control" id="new-etudiant-nationalite" name="nationalite">
    </div>
    <div class="mb-3">
        <label for="diplome" class="form-label">Diplome:</label>
        <input type="text" class="form-control" id="new-etudiant-diplome" name="diplome">
    </div>
    <div class="mb-3">
        <label for="genre" class="form-label">Genre:</label>
        <input type="text" class="form-control" id="new-etudiant-genre" name="genre">
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
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        // // Fonction de soumission du formulaire pour ajouter un nouvel étudiant
        // $("#add-new-etudiant").click(function(e){
        //     e.preventDefault();
        //     let form = $('#etudiant-add-form')[0];
        //     let data = new FormData(form); 

        //     $.ajax({
        //         url: "{{ route('etudiant.store') }}",
        //         type: "POST",
        //         data: data,
        //         dataType: "JSON",
        //         processData: false,
        //         contentType: false,
        //         success: function(response) {
        //             if (response.errors) {
        //                 var errorMsg = '';
        //                 $.each(response.errors, function(field, errors) {
        //                     $.each(errors, function(index, error) {
        //                         errorMsg += error + '<br>';
        //                     });
        //                 });
        //                 iziToast.error({
        //                     message: errorMsg,
        //                     position: 'topRight'
        //                 });
        //             } else {
        //                 iziToast.success({
        //                     message: response.success,
        //                     position: 'topRight'
        //                 });
        //                 $('#etudiantAddModal').modal('hide');
        //                 // Optionally reload the page or table data to reflect the new entry
        //                 location.reload();
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             iziToast.error({
        //                 message: 'An error occurred: ' + error,
        //                 position: 'topRight'
        //             });
        //         }
        //     });
        // });
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
                        // Optionally reload the page or table data to reflect the new entry
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

        // Edit student
        $('body').on('click', '#edit-etudiant', function () {
            var etudiantURL = $(this).data('url');

            $('#etudiantEditModal').modal('show');
            var tr = $(this).closest('tr');
            $('#etudiant-id').val(tr.find("td:nth-child(1)").text());
            $('#etudiant-nni').val(tr.find("td:nth-child(2)").text());
            $('#etudiant-nomprenom').val(tr.find("td:nth-child(3)").text());
            $('#etudiant-nationalite').val(tr.find("td:nth-child(4)").text());
            $('#etudiant-diplome').val(tr.find("td:nth-child(5)").text());
            $('#etudiant-genre').val(tr.find("td:nth-child(6)").text());
            $('#etudiant-lieunaissance').val(tr.find("td:nth-child(7)").text());
            $('#etudiant-adress').val(tr.find("td:nth-child(8)").text());
            $('#etudiant-age').val(tr.find("td:nth-child(9)").text());
            $('#etudiant-email').val(tr.find("td:nth-child(10)").text());
            $('#etudiant-phone').val(tr.find("td:nth-child(11)").text());
            $('#etudiant-wtsp').val(tr.find("td:nth-child(12)").text());
        });

        // Update student
        $('body').on('click', '#etudiant-update', function () {
            var id = $('#etudiant-id').val();
            var data = {
                nni: $('#etudiant-nni').val(),
                nomprenom: $('#etudiant-nomprenom').val(),
                nationalite: $('#etudiant-nationalite').val(),
                diplome: $('#etudiant-diplome').val(),
                genre: $('#etudiant-genre').val(),
                lieunaissance: $('#etudiant-lieunaissance').val(),
                adress: $('#etudiant-adress').val(),
                age: $('#etudiant-age').val(),
                email: $('#etudiant-email').val(),
                phone: $('#etudiant-phone').val(),
                wtsp: $('#etudiant-wtsp').val()
            };

            $.ajax({
                url: '/etudiants/' + id,
                type: 'PUT',
                dataType: 'json',
                data: data,
                success: function(response) {
                    $('#etudiantEditModal').modal('hide');
                    if (response.success) {
                        iziToast.success({
                            message: response.success,
                            position: 'topRight',
                        });
                        location.reload(); // Refresh the page to show the updated student
                    } else {
                        iziToast.error({
                            message: response.error,
                            position: 'topRight',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        message: 'An error occurred: ' + error,
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

        // Delete student
        // $('body').on('click', '#delete-etudiant', function (e) {
        //     e.preventDefault();
        //     var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet étudiant ?");
        //     if (confirmation) {
        //         $.ajax({
        //             url: $(this).attr('href'),
        //             type: 'DELETE',
        //             success: function(response) {
        //                 iziToast.success({
        //                     message: response.success,
        //                     position: 'topRight',
        //                 });
        //                 location.reload(); // Refresh the page to show the updated student list
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

        // Fade out alert
        var alertElement = document.querySelector('.fade-out');
        if (alertElement) {
            setTimeout(function() {
                alertElement.style.display = 'none';
            }, 2000);
        }
    });
</script>

<!-- <script type="text/javascript">

 



    document.addEventListener('DOMContentLoaded', function() {
        var deleteEtudiant = document.getElementById('deleteEtudiant');

        deleteEtudiant.addEventListener('click', function(event) {
            event.preventDefault();
            var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet étudiant ?");
            if (confirmation) {
                window.location.href = this.getAttribute('href');
            }
        });
    });

    // Sélectionnez l'élément avec la classe fade-out
    var alertElement = document.querySelector('.fade-out');

    // Vérifiez si l'élément existe
    if (alertElement) {
        // Masquez l'élément après 2 secondes
        setTimeout(function() {
            alertElement.style.display = 'none';
        }, 2000); // 2000 millisecondes équivalent à 2 secondes
    }


    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    /*------------------------------------------
    --------------------------------------------
    When click user on Add Button
    --------------------------------------------
    --------------------------------------------*/



    $("#add-new-etudiant").click(function(e){
        e.preventDefault();
        let form = $('#etudiant-add-form')[0];
        let data = new FormData(form); 
        
        $.ajax({
            url: "{{ route('etudiant.store') }}",
            type: "POST",
            data : data,
            dataType:"JSON",
            processData : false,
            contentType:false,
            
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
            position: 'topRight',
            
            
                    });
            }
                    
        },
        error: function(xhr, status, error) {
        
            iziToast.error({
                message: 'An error occurred: ' + error,
                position: 'topRight'
            });
        }
    
        });
    
    })



    /*------------------------------------------
    --------------------------------------------
    When click user on Edit Button
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '#edit-etudiant', function () {
        var etudiantURL = $(this).data('url');

        $('#etudiantEditModal').modal('show');
        $('#etudiant-id').val($(this).parents("tr").find("td:nth-child(1)").text());
        $('#etudiant-nni').val($(this).parents("tr").find("td:nth-child(2)").text());
        $('#etudiant-nomprenom').val($(this).parents("tr").find("td:nth-child(3)").text());
        $('#etudiant-nationalite').val($(this).parents("tr").find("td:nth-child(4)").text());
        $('#etudiant-diplome').val($(this).parents("tr").find("td:nth-child(5)").text()); // Utilisez le bon sélecteur pour le prénom
        $('#etudiant-genre').val($(this).parents("tr").find("td:nth-child(6)").text());
        $('#etudiant-lieunaissance').val($(this).parents("tr").find("td:nth-child(7)").text());
        $('#etudiant-adress').val($(this).parents("tr").find("td:nth-child(8)").text());
        $('#etudiant-age').val($(this).parents("tr").find("td:nth-child(9)").text());
        $('#etudiant-email').val($(this).parents("tr").find("td:nth-child(10)").text()); // Utilisez le bon sélecteur pour l'e-mail
        $('#etudiant-phone').val($(this).parents("tr").find("td:nth-child(11)").text()); // Utilisez le bon sélecteur pour le téléphone
        $('#etudiant-wtsp').val($(this).parents("tr").find("td:nth-child(12)").text()); // Utilisez le bon sélecteur pour le téléphone
        
    });

    /*------------------------------------------
    --------------------------------------------
    When click user on Update Button
    --------------------------------------------
    --------------------------------------------*/
    // Modifier le sélecteur pour cibler le bouton de mise à jour des étudiants
    $('body').on('click', '#etudiant-update', function () {
        var id = $('#etudiant-id').val();
        var nni = $('#etudiant-nni').val();
        var nomprenom = $('#etudiant-nomprenom').val();
        var nationalite = $('#etudiant-nationalite').val();
        var diplome = $('#etudiant-diplome').val();
        var genre = $('#etudiant-genre').val();
        var lieunaissance = $('#etudiant-lieunaissance').val();
        var adress = $('#etudiant-adress').val();
        var age = $('#etudiant-age').val();
        var email = $('#etudiant-email').val();
        var phone = $('#etudiant-phone').val();
        var wtsp = $('#etudiant-wtsp').val();

        $.ajax({
            url: '/etudiants/' + id,
            type: 'PUT',
            dataType: 'json',
            data: { nni: nni, nomprenom: nomprenom, nationalite: nationalite, diplome: diplome,genre: genre, lieunaissance: lieunaissance, adress: adress, age: age  , email: email, phone: phone, wtsp: wtsp },
            success: function(data) {
                $('#etudiantEditModal').modal('hide');

                // Mettre à jour les données dans le tableau sans recharger la page
                var rowIndex = $('#etudiant-id').closest('tr').index(); // Index de la ligne
                $('table tr').eq(rowIndex + 1).find('td:nth-child(2)').text(nni);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(3)').text(nomprenom);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(4)').text(nationalite);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(5)').text(diplome);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(6)').text(genre);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(7)').text(lieunaissance);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(8)').text(adress);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(9)').text(age);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(10)').text(email);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(11)').text(phone);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(12)').text(wtsp);
            },
            error: function(xhr, status, error) {
                // Gérer les erreurs de la requête AJAX
                console.error(xhr.responseText);
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

});

    
</script> -->
</body>
</html>











