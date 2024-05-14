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
        <p><strong>Nom:</strong> <br/> <input type="text" name="nom" id="etudiant-nom" class="form-control"></span></p>
        <p><strong>Prenom:</strong> <br/> <input type="text" name="prenom" id="etudiant-prenom" class="form-control"></span></p>
        <p><strong>Email:</strong> <br/> <input type="email" name="email" id="etudiant-email" class="form-control"></span></p>
        <p><strong>Telephone:</strong> <br/> <input type="text" name="telephone" id="etudiant-telephone" class="form-control"></span></p>
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
                        <label for="nom" class="form-label">Nom:</label>
                        <input type="text" class="form-control" id="new-etudiant-nom" name="nom">
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prenom:</label>
                        <input type="text" class="form-control" id="new-etudiant-prenom" name="prenom">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="new-etudiant-email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Telephone:</label>
                        <input type="text" class="form-control" id="new-etudiant-telephone" name="telephone">
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
        $('#etudiant-nom').val($(this).parents("tr").find("td:nth-child(2)").text());
        $('#etudiant-prenom').val($(this).parents("tr").find("td:nth-child(3)").text()); // Utilisez le bon sélecteur pour le prénom
        $('#etudiant-email').val($(this).parents("tr").find("td:nth-child(4)").text()); // Utilisez le bon sélecteur pour l'e-mail
        $('#etudiant-telephone').val($(this).parents("tr").find("td:nth-child(5)").text()); // Utilisez le bon sélecteur pour le téléphone
    });

    /*------------------------------------------
    --------------------------------------------
    When click user on Update Button
    --------------------------------------------
    --------------------------------------------*/
    // Modifier le sélecteur pour cibler le bouton de mise à jour des étudiants
    $('body').on('click', '#etudiant-update', function () {
        var id = $('#etudiant-id').val();
        var nom = $('#etudiant-nom').val();
        var prenom = $('#etudiant-prenom').val();
        var email = $('#etudiant-email').val();
        var telephone = $('#etudiant-telephone').val();

        $.ajax({
            url: '/etudiants/' + id,
            type: 'PUT',
            dataType: 'json',
            data: { nom: nom, prenom: prenom, email: email, telephone: telephone },
            success: function(data) {
                $('#etudiantEditModal').modal('hide');

                // Mettre à jour les données dans le tableau sans recharger la page
                var rowIndex = $('#etudiant-id').closest('tr').index(); // Index de la ligne
                $('table tr').eq(rowIndex + 1).find('td:nth-child(2)').text(nom);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(3)').text(prenom);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(4)').text(email);
                $('table tr').eq(rowIndex + 1).find('td:nth-child(5)').text(telephone);
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

    
</script>
</body>
</html>











