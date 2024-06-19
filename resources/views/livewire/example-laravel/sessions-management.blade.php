<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Sessions Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

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

<!-- Student Add Modal -->
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
                <div id="payment-form" style="display:none;">
                    <div class="form-group">
                        <label for="formation-price" class="form-label">Prix de la Formation:</label>
                        <input type="text" class="form-control" id="formation-price" readonly>
                    </div>
                    <div class="form-group">
                        <label for="prix-reel" class="form-label">Prix Réel:</label>
                        <input type="text" class="form-control" id="prix-reel" placeholder="Entrez le prix réel" readonly>
                    </div>
                    <div class="form-group">
                        <label for="montant-paye" class="form-label">Montant Payé:</label>
                        <input type="text" class="form-control" id="montant-paye" placeholder="Entrez le montant payé">
                    </div>
                    <div class="form-group">
                        <label for="reste-a-payer" class="form-label">Reste à Payer:</label>
                        <input type="text" class="form-control" id="reste-a-payer" readonly>
                    </div>
                    <div class="form-group">
                        <label for="mode-paiement" class="form-label">Mode de Paiement:</label>
                        <select class="form-control" id="mode-paiement">
                            @foreach ($modes_paiement as $mode)
                                <option value="{{ $mode->id }}">{{ $mode->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date-paiement" class="form-label">Date de Paiement:</label>
                        <input type="date" class="form-control" id="date-paiement">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="addEtudiantAndPaiement()">Ajouter Etudiant et Paiement</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Add Modal -->
<div class="modal fade" id="paiementAddModal" tabindex="-1" aria-labelledby="paiementAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paiementAddModalLabel">Ajouter un Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="etudiant-id">
                <input type="hidden" id="session-id">
                <div class="form-group">
                    <label for="montant-paye" class="form-label">Montant Payé:</label>
                    <input type="text" class="form-control" id="montant-paye" placeholder="Entrez le montant payé">
                </div>
                <div class="form-group">
                    <label for="mode-paiement" class="form-label">Mode de Paiement:</label>
                    <select class="form-control" id="mode-paiement">
                        @foreach ($modes_paiement as $mode)
                            <option value="{{ $mode->id }}">{{ $mode->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="date-paiement" class="form-label">Date de Paiement:</label>
                    <input type="date" class="form-control" id="date-paiement">
                </div>
                <button type="button" class="btn btn-primary" onclick="addPaiement()">Ajouter Paiement</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Prof Add Modal -->
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

<!-- Formation Content Container -->
<div id="formationContentContainer" style="display:none;">
    <h4>Liste des etudiants</h4>
    <div id="formationContents"></div>
</div>

<!-- Formation Prof Content Container -->
<div id="formationProfContentContainer" style="display:none;">
    <h4>Liste des Professeurs</h4>
    <div id="formationProfContents"></div>
</div>

<!-- Main Container -->
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
                            <input type="text" name="search6" id="search_bar" class="form-control" placeholder="Rechercher..." value="{{ isset($search6) ? $search6 : ''}}">
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

<!-- Session Add Modal -->
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

<!-- Session Edit Modal -->
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

        $('#search_bar').on('keyup', function(){
            var query = $(this).val();
            $.ajax({
                url: "{{ route('search6') }}",
                type: "GET",
                data: {'search6': query},
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

        
    });

    // Recherche d'étudiant par téléphone
    window.searchStudentByPhone = function() {
            const phone = $('#student-phone-search').val();
            if (phone) {
                $.ajax({
                    url: '/students/search',
                    type: 'POST',
                    data: {
                        phone: phone,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.student) {
                            const student = response.student;
                            $('#student-search-results').html(`
                                <div class="alert alert-success">Etudiant trouvé: ${student.nomprenom}</div>
                                <input type="hidden" id="student-id" value="${student.id}">
                            `);
                            loadFormationDetails();
                            $('#payment-form').show();
                        } else {
                            $('#student-search-results').html('<div class="alert alert-danger">Etudiant non trouvé.</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Erreur lors de la recherche de l\'étudiant: ' + error);
                    }
                });
            } else {
                alert('Veuillez entrer un numéro de téléphone.');
            }
        };

        function addEtudiantAndPaiement() {
    const etudiantId = $('#student-id').val();
    const sessionId = $('#new-student-session_id').val();
    const montantPaye = $('#montant-paye').val();
    const modePaiement = $('#mode-paiement').val();
    const datePaiement = $('#date-paiement').val();

    $.ajax({
        url: `/etudiants/${etudiantId}/sessions/${sessionId}/add`,
        type: 'POST',
        data: {
            montant_paye: montantPaye,
            mode_paiement: modePaiement,
            date_paiement: datePaiement,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#etudiantAddModal').modal('hide');
                showContents(sessionId);
            } else {
                alert(response.error);
            }
        },
        error: function(xhr, status, error) {
            alert('Erreur lors de l\'ajout de l\'étudiant et du paiement: ' + error);
        }
    });
}




        window.loadFormationDetails = function() {
            const sessionId = $('#new-student-session_id').val();
            $.ajax({
                url: `/formation/${sessionId}/details`,
                type: 'GET',
                success: function(response) {
                    if (response.formation) {
                        $('#formation-price').val(response.formation.prix);
                        $('#prix-reel').val(response.formation.prix);
                        calculateResteAPayer();
                    } else {
                        alert('Erreur lors du chargement des détails de la formation.');
                    }
                },
                error: function() {
                    alert('Erreur lors du chargement des détails de la formation.');
                }
            });
        };

        window.calculateResteAPayer = function() {
            const prixReel = parseFloat($('#prix-reel').val());
            const montantPaye = parseFloat($('#montant-paye').val());
            const resteAPayer = prixReel - montantPaye;
            $('#reste-a-payer').val(resteAPayer.toFixed(2));
        };

        // Paiement supplémentaire
        window.openAddPaymentModal = function(etudiantId, sessionId) {
            $('#etudiant-id').val(etudiantId);
            $('#session-id').val(sessionId);
            $('#paiementAddModal').modal('show');
        };

        window.addPaiement = function() {
            const etudiantId = $('#etudiant-id').val();
            const sessionId = $('#session-id').val();
            const montantPaye = $('#montant-paye').val();
            const modePaiement = $('#mode-paiement').val();
            const datePaiement = $('#date-paiement').val();

            $.ajax({
                url: `/etudiants/${etudiantId}/sessions/${sessionId}/paiement`,
                type: 'POST',
                data: {
                    montant_paye: montantPaye,
                    mode_paiement: modePaiement,
                    date_paiement: date_paiement,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#paiementAddModal').modal('hide');
                        showContents(sessionId);
                    } else {
                        alert(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erreur lors de l\'ajout du paiement: ' + error);
                }
            });
        };

        function showContents(sessionId) {
            $.ajax({
                url: `/sessions/${sessionId}/contents`,
                type: 'GET',
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                        return;
                    }

                    let html = `<div class="container-fluid py-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="card my-4">
                                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#etudiantAddModal" onclick="setSessionId(${sessionId})" data-toggle="tooltip" title="Ajouter un étudiant">Ajouter un étudiant</button>
                                            <button class="btn btn-secondary" onclick="hideStudentContents()">Fermer</button>
                                        </div>
                                    </div>
                                    <div class="card-body px-0 pb-2">
                                        <div class="table-responsive p-0" id="sessions-table">
                                            <table class="table align-items-center mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Nom & Prénom</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Prix Formation</th>
                                                        <th>Prix Réel</th>
                                                        <th>Montant Payé</th>
                                                        <th>Reste à Payer</th>
                                                        <th>Date de Paiement</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>`;

                    if (response.etudiants.length > 0) {
                        response.etudiants.forEach(function(content) {
                            let prixReel = content.paiements.length > 0 && content.paiements[0].prix_reel ? content.paiements[0].prix_reel : 0;
                            let montantPaye = content.paiements.reduce((total, paiement) => total + (paiement.montant_paye || 0), 0);
                            let resteAPayer = prixReel - montantPaye;

                            html += `<tr>
                                <td>${content.nomprenom}</td>
                                <td>${content.email}</td>
                                <td>${content.phone}</td>
                                <td>${prixReel}</td>
                                <td>${montantPaye}</td>
                                <td>${resteAPayer}</td>
                                <td>${content.paiements.map(p => p.date_paiement || '').join(', ')}</td>
                                <td>
                                    <button class="btn btn-dark" onclick="openAddPaymentModal(${content.id}, ${sessionId})"><i class="material-icons opacity-10">payment</i></button>
                                    <button class="btn btn-danger" onclick="deleteContent(${content.id})"><i class="material-icons opacity-10">delete_forever</i></button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        html += '<tr><td colspan="9" class="text-center">Aucun étudiant trouvé pour cette Formation.</td></tr>';
                    }

                    html += '</tbody></table></div></div></div></div></div>';
                    $('#formationContents').html(html);
                    $('#formationContentContainer').show();
                    $('html, body').animate({ scrollTop: $('#formationContentContainer').offset().top }, 'slow');
                },
                error: function() {
                    alert('Erreur lors du chargement des contenus.');
                }
            });
        }

        function hideStudentContents() {
            $('#formationContentContainer').hide();
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        }

        function setSessionId(sessionId) {
            $('#new-student-session_id').val(sessionId);
        }

        // Professeur functions
        function showProfContents(sessionId) {
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
                                            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#profAddModal" onclick="setProfSessionId(${sessionId})" data-toggle="tooltip" title="Ajouter un professeur"><i class="material-icons opacity-10">add</i></button>
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
                                                <tbody>`;

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
        }

        function hideProfContents() {
            $('#formationProfContentContainer').hide();
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        }

        function setProfSessionId(sessionId) {
            $('#new-prof-session_id').val(sessionId);
        }


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

        function addProfToSession(profId, sessionId) {
    $.ajax({
        url: `/sessions/${sessionId}/profs`,
        type: 'POST',
        data: {
            prof_id: profId,
            _token: $('meta[name="csrf-token"]').attr('content')
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
        error: function(xhr, status, error) {
            iziToast.error({
                message: 'Erreur lors de l\'ajout du professeur.',
                position: 'topRight'
            });
        }
    });
}

</script>
</body>
</html>
