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
                    <button type="button" class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#profAddModal"><i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter un Apprenant</button>
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
                                                Nom & Prenom</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                EMAIL</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Diplome</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Portable</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                WhatsApp</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Type</th>
                                            <!-- <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                CREATION DATE
                                            </th> -->
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($profs as $prof)
                                        <tr>
                                            <td>{{ $prof->id }}</td>
                                            <td>{{ $prof->nomprenom }}</td>
                                            <td>{{ $prof->email }}</td>
                                            <td>{{ $prof->diplome }}</td>
                                            <td>{{ $prof->phone }}</td>
                                            <td>{{ $prof->wtsp }}</td>
                                            <td>{{ $prof->typeymntprof_id }}</td>


                                            <td>
                                            <!-- <a href="javascript:void(0)" id="edit-prof" class="btn btn-info">Modifier</a> -->
                                            <a href="javascript:void(0)" id="edit-prof" class="btn btn-info">Modifier</a>

                                            <a href="/delete-prof/{{ $prof->id }}" id="delete-prof" class="btn btn-danger">Supprimer</a>
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

        <!-- Model modifier -->
<!-- <div class="modal fade" id="profEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
  
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modifier Professeur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  
      </div>
  
      <div class="modal-body">
        <input type="hidden" id="prof-id" name="id">
        <p><strong>Nom & Prenom:</strong> <br/> <input type="text" name="nomprenom" id="prof-nomprenom" class="form-control"></span></p>
        <p><strong>Email:</strong> <br/> <input type="email" name="email" id="prof-email" class="form-control"></span></p>
        <p><strong>Diplome:</strong> <br/> <input type="text" name="diplome" id="prof-diplome" class="form-control"></span></p>
        <p><strong>Portable:</strong> <br/> <input type="text" name="phone" id="prof-phone" class="form-control"></span></p>
        <p><strong>WhatsApp:</strong> <br/> <input type="text" name="wtsp" id="prof-wtsp" class="form-control"></span></p>
        <p><strong>Tyoe payment:</strong> <br/> <input type="text" name="type" id="prof-type" class="form-control"></span></p>

      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-info" id="prof-update">Modifier</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div> -->

<!-- Model ajouter -->

<!-- Ajouter un étudiant Modal -->
<!-- <div class="modal fade" id="profAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouvel Professeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="prof-add-form">
                    @csrf
                    <div class="mb-3">
                        <label for="nomprenom" class="form-label">Nom & Prenom:</label>
                        <input type="text" class="form-control" id="new-prof-nomprenom" name="nomprenom">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="new-prof-email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="diplome" class="form-label">Diplome:</label>
                        <input type="text" class="form-control" id="new-prof-diplome" name="diplome">
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Portable:</label>
                        <input type="text" class="form-control" id="new-prof-phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="wtsp" class="form-label">WhatsApp:</label>
                        <input type="text" class="form-control" id="new-prof-wtsp" name="wtsp">
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type payment:</label>
                        <input type="text" class="form-control" id="new-prof-type" name="type">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            
                <button type="button" class="btn btn-info" id="add-new-prof">Ajouter</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div> -->

<!-- Ajouter Professeur Modal -->
<div class="modal fade" id="profAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouveau Professeur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="prof-add-form">
                        @csrf
                        <div class="mb-3">
                            <label for="nomprenom" class="form-label">Nom & Prenom:</label>
                            <input type="text" class="form-control" id="new-prof-nomprenom" name="nomprenom" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="new-prof-email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="diplome" class="form-label">Diplome:</label>
                            <input type="text" class="form-control" id="new-prof-diplome" name="diplome" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Portable:</label>
                            <input type="text" class="form-control" id="new-prof-phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="wtsp" class="form-label">WhatsApp:</label>
                            <input type="text" class="form-control" id="new-prof-wtsp" name="wtsp" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type payment:</label>
                            <input type="text" class="form-control" id="new-prof-type" name="type" required>
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

    <!-- Modifier Professeur Modal -->


<!-- <div class="modal fade" id="profEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modifier Professeur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="prof-id" name="id">
        <p><strong>Nom & Prenom:</strong> <br/> <input type="text" name="nomprenom" id="prof-nomprenom" class="form-control"></p>
        <p><strong>Email:</strong> <br/> <input type="email" name="email" id="prof-email" class="form-control"></p>
        <p><strong>Diplome:</strong> <br/> <input type="text" name="diplome" id="prof-diplome" class="form-control"></p>
        <p><strong>Portable:</strong> <br/> <input type="text" name="phone" id="prof-phone" class="form-control"></p>
        <p><strong>WhatsApp:</strong> <br/> <input type="text" name="wtsp" id="prof-wtsp" class="form-control"></p>
        <p><strong>Type:</strong> <br/> <input type="text" name="typeymntprof_id" id="prof-type" class="form-control"></p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-info" id="prof-update">Modifier</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div> -->

<div class="modal fade" id="profEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
  
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modifier Professeur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  
      </div>
  
      <div class="modal-body">
        <input type="hidden" id="prof-id" name="id"></span>
        <p><strong>Nom & Prenom:</strong> <br/> <input type="text" name="nomprenom" id="prof-nomprenom" class="form-control"></span></p>
        <p><strong>Diplome:</strong> <br/> <input type="text" name="diplome" id="prof-diplome" class="form-control"></span></p>
        <p><strong>Email:</strong> <br/> <input type="email" name="email" id="prof-email" class="form-control"></span></p>
        <p><strong>Portable:</strong> <br/> <input type="text" name="phone" id="prof-phone" class="form-control"></span></p>
        <p><strong>WhatsApp:</strong> <br/> <input type="text" name="wtsp" id="prof-wtsp" class="form-control"></span></p>
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
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(response){
                    if(response.status == 400) {
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
                        $('#profAddModal').modal('hide');
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                }
            });
        });

        
        $('body').on('click', '#edit-prof', function () {
            var etudiantURL = $(this).data('url');

            $('#profEditModal').modal('show');
            var tr = $(this).closest('tr');
            $('#prof-id').val(tr.find("td:nth-child(1)").text());
            $('#prof-nomprenom').val(tr.find("td:nth-child(2)").text());
            $('#prof-diplome').val(tr.find("td:nth-child(3)").text());
            $('#prof-email').val(tr.find("td:nth-child(4)").text());
            $('#prof-phone').val(tr.find("td:nth-child(5)").text());
            $('#prof-wtsp').val(tr.find("td:nth-child(6)").text());
        });

        // Update student
        $('body').on('click', '#prof-update', function () {
            var id = $('#prof-id').val();
            var data = {
                nomprenom: $('#prof-nomprenom').val(),
                diplome: $('#prof-diplome').val(),
                email: $('#prof-email').val(),
                phone: $('#prof-phone').val(),
                wtsp: $('#prof-wtsp').val()
            };

            $.ajax({
                url: '/profs/' + id,
                type: 'PUT',
                dataType: 'json',
                data: data,
                success: function(response) {
                    $('#profEditModal').modal('hide');
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
            var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet Professeur ?");
            if (confirmation) {
                window.location.href = $(this).attr('href');
            }
        });
    });

 



//     document.addProfListener('DOMContentLoaded', function() {
//         var deleteProf = document.getElementById('deleteProf');

//         deleteProf.addProfListener('click', function(event) {
//             event.preventDefault();
//             var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet Professeur ?");
//             if (confirmation) {
//                 window.location.href = this.getAttribute('href');
//             }
//         });
//     });

//     // Sélectionnez l'élément avec la classe fade-out
//     var alertElement = document.querySelector('.fade-out');

//     // Vérifiez si l'élément existe
//     if (alertElement) {
//         // Masquez l'élément après 2 secondes
//         setTimeout(function() {
//             alertElement.style.display = 'none';
//         }, 2000); // 2000 millisecondes équivalent à 2 secondes
//     }


//     $(document).ready(function () {
//         $.ajaxSetup({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }
//         });

//     /*------------------------------------------
//     --------------------------------------------
//     When click user on Add Button
//     --------------------------------------------
//     --------------------------------------------*/



//     $("#add-new-prof").click(function(e){
//         e.preventDefault();
//         let form = $('#prof-add-form')[0];
//         let data = new FormData(form); 
        
//         $.ajax({
//             url: "{{ route('prof.store') }}",
//             type: "POST",
//             data : data,
//             dataType:"JSON",
//             processData : false,
//             contentType:false,
            
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
//             iziToast.success({
//             message: response.success,
//             position: 'topRight',
            
            
//                     });
//             }
                    
//         },
//         error: function(xhr, status, error) {
        
//             iziToast.error({
//                 message: 'An error occurred: ' + error,
//                 position: 'topRight'
//             });
//         }
    
//         });
    
//     })



//     /*------------------------------------------
//     --------------------------------------------
//     When click user on Edit Button
//     --------------------------------------------
//     --------------------------------------------*/
//     $('body').on('click', '#edit-prof', function () {
//         var profURL = $(this).data('url');

//         $('#profEditModal').modal('show');
//         $('#prof-id').val($(this).parents("tr").find("td:nth-child(1)").text());
//         $('#prof-nomprenom').val($(this).parents("tr").find("td:nth-child(2)").text());
//         $('#prof-email').val($(this).parents("tr").find("td:nth-child(4)").text()); // Utilisez le bon sélecteur pour l'e-mail
//         $('#prof-diplome').val($(this).parents("tr").find("td:nth-child(3)").text()); // Utilisez le bon sélecteur pour le prénom
//         $('#prof-phone').val($(this).parents("tr").find("td:nth-child(5)").text()); // Utilisez le bon sélecteur pour le téléphone
//         $('#prof-wtsp').val($(this).parents("tr").find("td:nth-child(5)").text()); // Utilisez le bon sélecteur pour le téléphone

//     });

//     /*------------------------------------------
//     --------------------------------------------
//     When click user on Update Button
//     --------------------------------------------
//     --------------------------------------------*/
//     // Modifier le sélecteur pour cibler le bouton de mise à jour des étudiants
//     $('body').on('click', '#prof-update', function () {
//         var id = $('#prof-id').val();
//         var nomprenom = $('#prof-nomprenom').val();
//         var email = $('#prof-email').val();
//         var diplome = $('#prof-diplome').val();
//         var phone = $('#prof-phone').val();
//         var wtsp = $('#prof-wtsp').val();


//         $.ajax({
//             url: '/profs/' + id,
//             type: 'PUT',
//             dataType: 'json',
//             data: { nom: nom, prenom: prenom, email: email, telephone: telephone },
//             success: function(data) {
//                 $('#profEditModal').modal('hide');

//                 // Mettre à jour les données dans le tableau sans recharger la page
//                 var rowIndex = $('#prof-id').closest('tr').index(); // Index de la ligne
//                 $('table tr').eq(rowIndex + 1).find('td:nth-child(2)').text(nomprenom);
//                 $('table tr').eq(rowIndex + 1).find('td:nth-child(4)').text(email);
//                 $('table tr').eq(rowIndex + 1).find('td:nth-child(3)').text(diplome);
//                 $('table tr').eq(rowIndex + 1).find('td:nth-child(5)').text(phone);
//                 $('table tr').eq(rowIndex + 1).find('td:nth-child(5)').text(wtsp);

//             },
//             error: function(xhr, status, error) {
//                 // Gérer les erreurs de la requête AJAX
//                 console.error(xhr.responseText);
//             }
            
//         });
//     });
//     $('body').on('click', '#delete-prof', function (e) {
//     e.preventDefault();
//     var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet étudiant ?");
//     if (confirmation) {
//         window.location.href = $(this).attr('href');
//     }
// });

// });

    
</script>
</body>
</html>











