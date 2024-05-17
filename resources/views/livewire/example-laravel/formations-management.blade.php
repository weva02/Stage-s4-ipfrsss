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
                    <button type="button" class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#formationAddModal"><i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter un Apprenant</button>
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
                                                Code</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Nom</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Duree</th>
                                            <!-- <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Portable</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                WhatsApp</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Type</th> -->
                                            <!-- <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                CREATION DATE
                                            </th> -->
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($formations as $formation)
                                        <tr>
                                            <td>{{ $formation->id }}</td>
                                            <td>{{ $formation->code }}</td>
                                            <td>{{ $formation->nom }}</td>
                                            <td>{{ $formation->duree }}</td>


                                            <td>
                                            <!-- <a href="javascript:void(0)" id="edit-prof" class="btn btn-info">Modifier</a> -->
                                            <a href="javascript:void(0)" id="edit-formation" class="btn btn-info">Modifier</a>

                                            <a href="/delete-formation/{{ $formation->id }}" id="delete-formation" class="btn btn-danger">Supprimer</a>
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

        <!-- Model modifier -->




<!-- Ajouter formation Modal -->
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
                            <input type="text" class="form-control" id="new-formation-code" name="code" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="new-prof-email" name="email" required>
                        </div> -->
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom:</label>
                            <input type="text" class="form-control" id="new-formation-nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="duree" class="form-label">Duree:</label>
                            <input type="text" class="form-control" id="new-formation-duree" name="duree" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="wtsp" class="form-label">WhatsApp:</label>
                            <input type="text" class="form-control" id="new-prof-wtsp" name="wtsp" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type payment:</label>
                            <input type="text" class="form-control" id="new-prof-type" name="type" required>
                        </div> -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="add-new-formation">Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modifier formation Modal -->




<div class="modal fade" id="formationEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
  
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modifier Formation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  
      </div>
  
      <div class="modal-body">
        <input type="hidden" id="formation-id" name="id"></span>
        <p><strong>Code:</strong> <br/> <input type="text" name="code" id="formation-code" class="form-control"></span></p>
        <!-- <p><strong>Email:</strong> <br/> <input type="email" name="email" id="prof-email" class="form-control"></span></p> -->
        <p><strong>Nom:</strong> <br/> <input type="text" name="nom" id="formation-nom" class="form-control"></span></p>
        <p><strong>Duree:</strong> <br/> <input type="text" name="duree" id="formation-duree" class="form-control"></span></p>
        <!-- <p><strong>WhatsApp:</strong> <br/> <input type="text" name="wtsp" id="prof-wtsp" class="form-control"></span></p> -->
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

        $("#add-new-formation").click(function(e){
            e.preventDefault();
            let form = $('#formation-add-form')[0];
            let data = new FormData(form); 
            
            $.ajax({
                url: "{{ route('formation.store') }}",
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
                        $('#formationAddModal').modal('hide');
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                }
            });
        });

        
        $('body').on('click', '#edit-formation', function () {
            var formationURL = $(this).data('url');

            $('#formationEditModal').modal('show');
            var tr = $(this).closest('tr');
            $('#formation-id').val(tr.find("td:nth-child(1)").text());
            $('#formation-code').val(tr.find("td:nth-child(2)").text());
            $('#formation-nom').val(tr.find("td:nth-child(3)").text());
            $('#formation-duree').val(tr.find("td:nth-child(4)").text());
            // $('#prof-phone').val(tr.find("td:nth-child(5)").text());
            // $('#prof-wtsp').val(tr.find("td:nth-child(6)").text());
        });

        // Update student
        $('body').on('click', '#formation-update', function () {
            var id = $('#formation-id').val();
            var data = {
                code: $('#formation-code').val(),
                nom: $('#formation-nom').val(),
                duree: $('#formation-duree').val(),
                // phone: $('#prof-phone').val(),
                // wtsp: $('#prof-wtsp').val()
            };

            $.ajax({
                url: '/formations/' + id,
                type: 'PUT',
                dataType: 'json',
                data: data,
                success: function(response) {
                    $('#formationEditModal').modal('hide');
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
            var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet Formation ?");
            if (confirmation) {
                window.location.href = $(this).attr('href');
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











