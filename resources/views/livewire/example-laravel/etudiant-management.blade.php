
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
                        <div class=" me-3 my-3 text-end">
                            <a class="btn bg-gradient-dark mb-0" href="{{ route('add-etudiant') }}"><i
                                    class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter un Apprenant</a>
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
                                                Nom</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Prenom</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                EMAIL</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Phone</th>
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
                                            <td>{{ $etudiant->nom }}</td>
                                            <td>{{ $etudiant->prenom }}</td>
                                            <td>{{ $etudiant->email }}</td>
                                            <td>{{ $etudiant->telephone }}</td>
                                            <td>
                                                <a href="/update-etudiant/{{ $etudiant->id }}" class="btn btn-info">Update</a>
                                                <a href="/delete-etudiant/{{ $etudiant->id }}" class="btn btn-danger" id="deleteEtudiant">Delete</a>
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for create -->
<div class="modal fade" id="addEtudiant" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Ajouter un nouveau Etudiant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="etudiantForm">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nom</label>
                        <input type="text" onblur="" name="firstName" class="form-control" id="firstName" placeholder="Enter First Name">
                        <small id="nom_error" class="form-text text-danger"></small>

                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Prenom</label>
                        <input type="text" name="prenom" class="form-control" id="prenom" placeholder="Enter Middle Name">
                        <small id="prenom_error" class="form-text text-danger"></small>

                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter Last Name">
                        <small id="email_error" class="form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Telephone </label>
                        <input type="text" name="telephone" class="form-control" id="telephone" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        <small id="telephone_error" class="form-text text-danger"></small>

                    </div>
                    <!-- <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        <small id="password_error" class="form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="exampleInputPassword1" placeholder="Confirm Your Password">
                        <small id="password_confirmation_error" class="form-text text-danger"></small>
                    </div> -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="save_etudiant" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal for edit -->
<div class="modal fade" id="editEtudiant" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modifier un Etudiant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <div class="form-group">
                        <label for="NomEdit">Nom</label>
                        <input type="text" onblur="" name="nomupdate" class="form-control" id="nomupdate" placeholder="Enter First Name">
                        <small id="nom_edit_error" class="form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="PrenomEdit">Prenom</label>
                        <input type="text" name="prenomupdate" class="form-control" id="prenomupdate" placeholder="Enter Middle Name">
                        <small id="prenom_edit_error" class="form-text text-danger"></small>

                    </div>
                    <div class="form-group">
                        <label for="EmailEdit">Email</label>
                        <input type="text" name="emailupdate" class="form-control" id="emailupdate" placeholder="Enter Last Name">
                        <small id="email_edit_error" class="form-text text-danger"></small>
                    </div>
                    <input type="hidden" id="etudiantIdEdit" name="etudiantIdEdit" value="">
                    <div class="form-group">
                        <label for="TelephoneEdit">Telephone</label>
                        <input type="email" name="telephoneupdate" class="form-control" id="telephoneupdate" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        <small id="telephone_edit_error" class="form-text text-danger"></small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button"  id="update_etudiant" class="btn btn-primary">Save changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>



<script>

    $(document).on('click', '#save_etudiant', function(e){
        e.preventDefault();
        $('#nom_error').text('');
        $('#prenom_error').text('');
        $('#email_error').text('');
        $('#telephone_error').text('');
        // $('#password_error').text('');
        // $('#password_confirmation_error').text('');

        var formData = new FormData($('#etudiantForm')[0]);
        console.log(formData)

        $.ajax({
            type: 'post',
            enctype: 'multipart/form-data',
            url: "{{route('register.create')}}",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function (response){
                if(response){
                    $("#etudiantTable tbody").prepend('<tr><td>'+response.nom+'</td><td>'+response.prenom+'</td><td>'+response.email+'</td><td>'+response.telephone+'</td></tr>')
                    $('#etudiantForm')[0].reset();
                    $('#addEtudiant').modal('hide');
                    $('#msg-succ').show();

                }

            }, error: function (reject){
                    var response = $.parseJSON(reject.responseText);
                    $.each(response.errors, function(key, val){
                        $("#" + key + "_error").text(val[0]);
                    });
            }
        });
    });



    $('body').on('click', '#getEtudiant', function (event) {
        event.preventDefault();
        var etudiant_id = $(this).data('id');
        console.log(etudiant_id)
        $.get('register-edit/' + etudiant_id, function (data) {
            $('#NomEdit').val(data.nom);
            $('#PrenomEdit').val(data.prenom);
            $('#EmailEdit').val(data.email);
            $('#TelephoneEdit').val(data.telephone);
            $('#etudiantIdEdit').val(etudiant_id);


        })
    });



    $(document).on('click', '#update_etudiant', function(e){
        e.preventDefault();
        $('#nom_edit_error').text('');
        $('#prenom_edit_error').text('');
        $('#email_edit_errorr').text('');
        $('#telephone_edit_error').text('');

    //    var formData = new FormData($('#editForm')[0]);
        var nom = $("#NomEdit").val();
        var prenom = $("#PrenomEdit").val();
        var email = $("#EmailEdit").val();
        var telephone= $("#TelephoneEdit").val();
        var id = $("#IdEdit").val();


        console.log(nom)
        console.log(prenom)
        console.log(id)

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'post',
            enctype: 'multipart/form-data',
            url: "{{url('register-update')}}" +'/'+ etudiant_id,
            data: {
                nomupdate:nom,
                prenomupdate:prenom,
                emailupdate:email,
                telephoneupdate:telephone,
            },
            processData: false,
            contentType: false,
            cache: false,
            success: function (response){
                if(response){
                    $("#etudiantTable tbody").prepend('<tr><td>'+response.nom+'</td><td>'+response.prenom+'</td><td>'+response.email+'</td><td>'+response.telephone+'</td></tr>')
                    $('#etudiantForm')[0].reset();
                    $('#editEtudiant').modal('hide');
                    $('#msg-succ').show();
                }

            }, error: function (reject){
                var response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val){
                    $("#" + key + "_edit_error").text(val[0]);
                });
            }
        });
    });

</script>
