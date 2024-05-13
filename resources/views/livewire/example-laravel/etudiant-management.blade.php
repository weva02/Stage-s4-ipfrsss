
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
<!-- <div class="modal fade" id="addEtudiant" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="save_etudiant" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div> -->

<!-- Modal for edit -->
<!-- <div class="modal fade" id="editEtudiant" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button"  id="update_etudiant" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Fonction pour ajouter un étudiant
        $(document).on('click', '#save_etudiant', function(e){
            e.preventDefault();
            // Votre code AJAX pour ajouter un étudiant
        });

        // Fonction pour pré-remplir le formulaire de modification d'un étudiant
        $(document).on('click', '.editEtudiant', function(e){
            e.preventDefault();
            // Votre code AJAX pour récupérer les données de l'étudiant à modifier et pré-remplir le formulaire
        });

        // Fonction pour mettre à jour un étudiant
        $(document).on('click', '#update_etudiant', function(e){
            e.preventDefault();
            // Votre code AJAX pour mettre à jour un étudiant
        });

        // Fonction pour supprimer un étudiant
        $(document).on('click', '.deleteEtudiant', function(e){
            e.preventDefault();
            var etudiant_id = $(this).data('id');
            // Votre code AJAX pour supprimer un étudiant
        });
    });
</script> -->
