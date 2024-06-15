<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Sessions Management</title>
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
     <!-- Modal Ajouter Etudiant -->
    
     <div class="modal fade" id="etudiantAddModal" tabindex="-1" aria-labelledby="etudiantAddModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="etudiantAddModalLabel">Ajouter un étudiant à la formation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="new-student-session_id">
                    <div class="form-group">
                        <label for="student-phone-search" class="form-label">Numéro de téléphone de l'étudiant:</label>
                        <input type="text" class="form-control" id="student-phone-search" placeholder="Entrez le numéro de téléphone">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="searchStudentByPhone()">Rechercher</button>
                    <div id="student-search-results"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter Professeur -->
<div class="modal fade" id="profAddModal" tabindex="-1" aria-labelledby="profAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profAddModalLabel">Ajouter un Professeur à la formation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="new-prof-session_id">
                <div class="form-group">
                    <label for="prof-phone-search" class="form-label">Numéro de téléphone du Professeur:</label>
                    <input type="text" class="form-control" id="prof-phone-search" placeholder="Entrez le numéro de téléphone">
                </div>
                <button type="button" class="btn btn-primary" onclick="searchProfByPhone()">Rechercher</button>
                <div id="prof-search-results"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>


    <div id="formationContentContainer" style="display:none;">
        <h4>Liste des etudiants</h4>
        <div id="formationContents"></div>
    </div>

    <div id="formationProfContentContainer" style="display:none;">
        <h4>Liste des Professeurs</h4>
        <div id="formationProfContents"></div>
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if (session('status'))
                <div class="alert alert-success fade-out">
                    {{ session('status')}}
                </div>
                @endif
                @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
                @endif
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#sessionAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter 
                            </button>
                            <a href="#" class="btn btn-success">Exporter </a>
                        </div>
                        <form class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search3" id="search_bar" class="form-control" placeholder="Rechercher...">
                            </div>
                        </form>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0" id="sessions-table">
                            @include('livewire.example-laravel.sessions-list', ['sessions' => $sessions])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter session -->
    <div class="modal fade" id="sessionAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouveau Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="session-add-form">
                        @csrf
                        <div class="row mb-2">
                        <div class="form-group col-md-6">
                            <label for="formation_id" class="form-label required">Programme</label>
                            <select class="form-control" id="new-session-formation_id" name="formation_id">
                                <option value="">Sélectionner Programme</option>
                                @foreach ($formations as $formation)
                                    <option value="{{ $formation->id }}">{{ $formation->nom }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="formation_id-warning"></div>

                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom:</label>
                            <input type="text" class="form-control" id="new-session-nom" placeholder="nom" name="nom">
                            <div class="text-danger" id="nom-warning"></div>

                        </div>
                        </div>
                        <div class="row mb-2">

                        <div class="col-md-6">
                            <label for="date_debut" class="form-label required">Date debut:</label>
                            <input type="date" class="form-control" id="new-session-date_debut" name="date_debut">
                            <div class="text-danger" id="date_debut-warning"></div>

                        </div>
                        <div class="col-md-6">
                            <label for="date_fin" class="form-label required">Date fin:</label>
                            <input type="date" class="form-control" id="new-session-date_fin"  name="date_fin">
                            <div class="text-danger" id="date_fin-warning"></div>

                        </div>
                        </div>
                        
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="add-new-session">Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modifier session -->
    <div class="modal fade" id="sessionEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="session-edit-form">
                        @csrf
                        <input type="hidden" id="session-id" name="id">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="formation_id" class="form-label required">Programme</label>
                                <select class="form-control" id="session-formation_id" name="formation_id">
                                    <option value="">Sélectionner Programme</option>
                                    @foreach ($formations as $formation)
                                        <option value="{{ $formation->id }}">{{ $formation->nom }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger" id="edit-formation_id-warning"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label required">Nom :</label>
                                <input type="text" class="form-control" id="session-nom" name="nom">
                                <div class="text-danger" id="edit-nom-warning"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="date_debut" class="form-label required">Date debut :</label>
                                <input type="date" class="form-control" id="session-date_debut" name="date_debut">
                                <div class="text-danger" id="edit-date_debut-warning"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="date_fin" class="form-label required">Date fin:</label>
                                <input type="date" class="form-control" id="session-date_fin" name="date_fin">
                                <div class="text-danger" id="edit-date_fin-warning"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="session-update">Modifier</button>
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

            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });

            // Recherche AJAX
            $('#search_bar').on('keyup', function(){
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('search3') }}",
                    type: "GET",
                    data: {'search3': query},
                    success: function(data){
                        $('#sessions-table').html(data.html);
                    }
                });
            });

            function validateForm(formId, warnings) {
                let isValid = true;
                for (let field in warnings) {
                    const input = $(formId + ' #' + field);
                    const warning = $(warnings[field]);
                    if (input.val().trim() === '') {
                        warning.text('Ce champ est requis.');
                        isValid = false;
                    } else {
                        warning.text('');
                    }
                }
                return isValid;
            }

            window.showContents = function(sessionId) {
                $.ajax({
                    url: `/sessions/${sessionId}/contents`,
                    type: 'GET',
                    success: function(response) {
                        if (response.error) {
                            alert(response.error);
                            return;
                        }

                        let html = `
                        <div class="container-fluid py-4">
                        <div class="row">
                            <div class="col-12">
                            <div class="card my-4">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                            <div>
                            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#etudiantAddModal" onclick="setSessionId(${sessionId})" data-toggle="tooltip" title="ajouter un etudiant">Ajouter un étudiant</button>
                            <button class="btn btn-secondary" onclick="hideStudentContents()">Fermer</button>
                            </div>
                            </div>
                            <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0" id="sessions-table">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Nom & Prénom</th>
                                        <th>Genre</th>
                                        <th>Date naissance</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>WhatsApp</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </div>
                                </div>
                                </div>
                                </div>
                                </div>`;

                        if (response.etudiant.length > 0) {
                            response.etudiant.forEach(function(content) {
                                html += `<tr>
                                    <td>${content.nomprenom}</td>
                                    <td>${content.genre}</td>
                                    <td>${content.datenaissance}</td>
                                    <td>${content.email}</td>
                                    <td>${content.phone}</td>
                                    <td>${content.wtsp}</td>
                                    <td>
                                        <button class="btn btn-danger" onclick="deleteContent(${content.id})"><i class="material-icons opacity-10">delete_forever</i></button>
                                    </td>
                                </tr>`;
                            });
                        } else {
                            html += '<tr><td colspan="8" class="text-center">Aucun étudiant trouvé pour cette Formation.</td></tr>';
                        }

                        html += '</tbody></table>';
                        $('#formationContents').html(html);
                        $('#formationContentContainer').show();
                        $('html, body').animate({ scrollTop: $('#formationContentContainer').offset().top }, 'slow');
                    },
                    error: function() {
                        alert('Erreur lors du chargement des contenus.');
                    }
                });
            };
            window.hideStudentContents = function() {
                $('#formationContentContainer').hide();
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            };

            // Set the session ID for adding students
            window.setSessionId = function(sessionId) {
                $('#new-student-session_id').val(sessionId);
            };

            // Search and add student by phone
            window.searchStudentByPhone = function() {
                const phone = $('#student-phone-search').val();
                if (phone) {
                    $.ajax({
                        url: '/students/search',
                        type: 'GET',
                        data: { phone: phone },
                        success: function(response) {
                            if (response.student) {
                                $('#student-search-results').html(`
                                    <div class="alert alert-success">
                                        Étudiant trouvé: ${response.student.nomprenom} (${response.student.email})
                                        <button class="btn btn-primary" onclick="addStudentToSession(${response.student.id}, ${$('#new-student-session_id').val()})">Ajouter</button>
                                    </div>
                                `);
                            } else {
                                $('#student-search-results').html('<div class="alert alert-danger">Étudiant non trouvé.</div>');
                            }
                        },
                        error: function() {
                            $('#student-search-results').html('<div class="alert alert-danger">Erreur lors de la recherche.</div>');
                        }
                    });
                }
            };

            // Add student to session
            window.addStudentToSession = function(studentId, sessionId) {
                $.ajax({
                    url: '/sessions/' + sessionId + '/students',
                    type: 'POST',
                    data: {
                        student_id: studentId
                    },
                    success: function(response) {
                        if (response.success) {
                            iziToast.success({
                                message: response.success,
                                position: 'topRight'
                            });
                            $('#etudiantAddModal').modal('hide');
                            showContents(sessionId);
                        } else {
                            iziToast.error({
                                message: response.error,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function() {
                        iziToast.error({
                            message: 'Erreur lors de l\'ajout de l\'étudiant.',
                            position: 'topRight'
                        });
                    }
                });
            };

            window.showProfContents = function(sessionId) {
    $.ajax({
        url: `/sessions/${sessionId}/profs`,
        type: 'GET',
        success: function(response) {
            if (response.error) {
                alert(response.error);
                return;
            }

            let html = `
            <div class="container-fluid py-4">
                <div class="row">
                <div class="col-12">
                <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#profAddModal" onclick="setProfSessionId(${sessionId})" data-toggle="tooltip" title="ajouter un professeur"><i class="material-icons opacity-10">add</i></button>
                    <button class="btn btn-secondary" onclick="hideProfContents()">Fermer</button>
                </div>
                </div>
                <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0" id="sessions-table">

                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th>Nom & Prénom</th>
                            <th>Genre</th>
                            <th>Date naissance</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>WhatsApp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>`;

            if (response.prof.length > 0) {
                response.prof.forEach(function(content) {
                    html += `<tr>
                        <td>${content.nomprenom}</td>
                        <td>${content.genre}</td>
                        <td>${content.datenaissance}</td>
                        <td>${content.email}</td>
                        <td>${content.phone}</td>
                        <td>${content.wtsp}</td>
                        <td>
                            <button class="btn btn-danger" onclick="deleteProfContent(${content.id})"><i class="material-icons opacity-10">delete_forever</i></button>
                        </td>
                    </tr>`;
                });
            } else {
                html += '<tr><td colspan="8" class="text-center">Aucun professeur trouvé pour cette Formation.</td></tr>';
            }

            html += '</tbody></table>';
            $('#formationProfContents').html(html);
            $('#formationProfContentContainer').show();
            $('html, body').animate({ scrollTop: $('#formationProfContentContainer').offset().top }, 'slow');
        },
        error: function() {
            alert('Erreur lors du chargement des contenus.');
        }
    });
};
window.hideProfContents = function() {
    $('#formationProfContentContainer').hide();
    $('html, body').animate({ scrollTop: 0 }, 'slow');
};

// Function to hide the professor contents section
window.hideProfContents = function() {
    $('#formationProfContentContainer').hide();
    $('html, body').animate({ scrollTop: 0 }, 'slow');
};


            // Set the session ID for adding professors
            window.setProfSessionId = function(sessionId) {
                $('#new-prof-session_id').val(sessionId);
            };

            // Search and add professor by phone
            window.searchProfByPhone = function() {
                const phone = $('#prof-phone-search').val();
                if (phone) {
                    $.ajax({
                        url: '/profs/search',
                        type: 'GET',
                        data: { phone: phone },
                        success: function(response) {
                            if (response.prof) {
                                $('#prof-search-results').html(`
                                    <div class="alert alert-success">
                                        Professeur trouvé: ${response.prof.nomprenom} (${response.prof.email})
                                        <button class="btn btn-primary" onclick="addProfToSession(${response.prof.id}, ${$('#new-prof-session_id').val()})">Ajouter</button>
                                    </div>
                                `);
                            } else {
                                $('#prof-search-results').html('<div class="alert alert-danger">Professeur non trouvé.</div>');
                            }
                        },
                        error: function() {
                            $('#prof-search-results').html('<div class="alert alert-danger">Erreur lors de la recherche.</div>');
                        }
                    });
                }
            };

            // Add professor to session
            window.addProfToSession = function(profId, sessionId) {
                $.ajax({
                    url: '/sessions/' + sessionId + '/profs',
                    type: 'POST',
                    data: {
                        prof_id: profId
                    },
                    success: function(response) {
                        if (response.success) {
                            iziToast.success({
                                message: response.success,
                                position: 'topRight'
                            });
                            $('#profAddModal').modal('hide');
                            showProfContents(sessionId);
                        } else {
                            iziToast.error({
                                message: response.error,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function() {
                        iziToast.error({
                            message: 'Erreur lors de l\'ajout du professeur.',
                            position: 'topRight'
                        });
                    }
                });
            };

            

            $("#add-new-session").click(function(e){
                e.preventDefault();
                if (!validateForm('#session-add-form', {
                    'new-session-formation_id': '#formation_id-warning',
                    'new-session-nom': '#nom-warning',
                    'new-session-date_debut': '#date_debut-warning',
                    'new-session-date_fin': '#date_fin-warning'
                })) {
                    return;
                }

                let form = $('#session-add-form')[0];
                let data = new FormData(form);

                $.ajax({
                    url: "{{ route('session.store') }}",
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
                            $('#sessionAddModal').modal('hide');
                            location.reload();
                            addSessionToTable(response.session);
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

            $('body').on('click', '#edit-session', function () {
                var tr = $(this).closest('tr');
                $('#session-id').val(tr.find("td:nth-child(1)").text());
                $('#session-formation_id').val(tr.find("td:nth-child(2)").data('formation-id'));
                $('#session-nom').val(tr.find("td:nth-child(3)").text());
                $('#session-date_debut').val(tr.find("td:nth-child(4)").text());
                $('#session-date_fin').val(tr.find("td:nth-child(5)").text());

                $('#sessionEditModal').modal('show');
            });

            $('body').on('click', '#session-update', function () {
                if (!validateForm('#session-edit-form', {
                    'session-formation_id': '#edit-formation_id-warning',
                    'session-nom': '#edit-nom-warning',
                    'session-date_debut': '#edit-date_debut-warning',
                    'session-date_fin': '#edit-date_fin-warning'
                })) {
                    return;
                }
                var id = $('#session-id').val();
                var formData = new FormData($('#session-edit-form')[0]);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('session.update', '') }}/" + id,
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#sessionEditModal').modal('hide');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        if (response.success) {
                            iziToast.success({
                                message: response.success,
                                position: 'topRight'
                            });
                        } else {
                            iziToast.error({
                                message: response.error,
                                position: 'topRight',
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

            $('body').on('click', '#delete-session', function (e) {
                e.preventDefault();
                var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce session ?");
                if (confirmation) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('session.delete', '') }}/" + id,
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
