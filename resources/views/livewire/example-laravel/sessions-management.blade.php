<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Sessions Management</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

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

<!-- Add Student Modal -->
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
                <div class="row mb-3">
                    <div class="form-group col-md-4">
                        <label for="formation-price" class="form-label">Prix Programme:</label>
                        <input type="text" class="form-control" id="formation-price" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="prix-reel" class="form-label">Prix Réel:</label>
                        <input type="text" class="form-control" id="prix-reel" placeholder="Entrez le prix réel">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="montant-paye" class="form-label">Montant Payé:</label>
                        <input type="text" class="form-control" id="montant-paye" placeholder="Entrez le montant payé">
                    </div>
                    </div>
                    <div class="row mb-2">
                    <div class="form-group col-md-6">
                        <label for="mode-paiement" class="form-label">Mode de Paiement:</label>
                        <select class="form-control" id="mode-paiement">
                            @foreach ($modes_paiement as $mode)
                                <option value="{{ $mode->id }}">{{ $mode->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="date-paiement" class="form-label">Date de Paiement:</label>
                        <input type="date" class="form-control" id="date-paiement">
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-info" onclick="addEtudiantAndPaiement()">Ajouter Etudiant et Paiement</button>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
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
                <input type="hidden" id="prix-formation">
                <input type="hidden" id="prix-reel">
                <input type="hidden" id="reste-a-payer">
                <div class="form-group">
                    <label for="nouveau-montant-paye" class="form-label">Nouveau Montant Payé:</label>
                    <input type="text" class="form-control" id="nouveau-montant-paye" placeholder="Entrez le montant payé">
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
    <input type="date" class="form-control" id="date-paiement" name="date_paiement">
</div>



                <button type="button" class="btn btn-primary" onclick="addPaiement()">Ajouter Paiement</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>






<!-- Add Professor Modal -->
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
                            <input type="date" class="form-control" id="new-session-date_fin" name="date_fin">
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

    $("#add-new-session").click(function(e){
        e.preventDefault();
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

    // $('body').on('click', '#delete-session', function (e) {
    //     e.preventDefault();
    //     var id = $(this).data('id');
    //     var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cette session ?");

    //     if (confirmation) {
    //         $.ajax({
    //             url: "{{ route('session.delete', '') }}/" + id,
    //             type: 'DELETE',
    //             success: function(response) {
    //                 if(response.status === 400) {
    //                     iziToast.error({
    //                         message: response.message,
    //                         position: 'topRight'
    //                     });
    //                 } else {
    //                     iziToast.success({
    //                         message: response.success,
    //                         position: 'topRight'
    //                     });
    //                     location.reload();
    //                 }
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

    $('body').on('click', '#delete-session', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: "{{ route('session.delete', '') }}/" + id,
            type: 'DELETE',
            success: function(response) {
                if (response.status === 400) {
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                    location.reload();
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

    window.setSessionId = function(sessionId) {
        $('#new-student-session_id').val(sessionId);
    }

    window.searchStudentByPhone = function() {
        const phone = $('#student-phone-search').val();
        const sessionId = $('#new-student-session_id').val();

        if (phone) {
            $.ajax({
                url: '{{ route("etudiant.search") }}',
                type: 'POST',
                data: { phone: phone },
                success: function(response) {
                    if (response.etudiant) {
                        const etudiant = response.etudiant;
                        $.ajax({
                            url: `/sessions/${sessionId}/check-student`,
                            type: 'POST',
                            data: { etudiant_id: etudiant.id },
                            success: function(checkResponse) {
                                if (checkResponse.isInSession) {
                                    $('#student-search-results').html('<div class="alert alert-danger">L\'étudiant est déjà inscrit dans cette session.</div>');
                                } else {
                                    $('#student-search-results').html(
                                        `<div class="alert alert-success">Etudiant trouvé: ${etudiant.nomprenom}</div>
                                        <input type="hidden" id="etudiant-id" value="${etudiant.id}">`
                                    );
                                    loadFormationDetails();
                                    $('#payment-form').show();
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Erreur lors de la vérification de l\'étudiant dans la session: ' + error);
                            }
                        });
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
    }

    // window.loadFormationDetails = function() {
    //     const sessionId = $('#new-student-session_id').val();
    //     $.ajax({
    //         url: `{{ route("sessions.details", ":sessionId") }}`.replace(':sessionId', sessionId),
    //         type: 'GET',
    //         success: function(response) {
    //             if (response.formation) {
    //                 $('#formation-price').val(response.formation.prix);
    //             } else {
    //                 $('#formation-price').val('');
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             alert('Erreur lors du chargement des détails de la formation: ' + error);
    //         }
    //     });
    // }

    window.loadFormationDetails = function() {
        const sessionId = $('#new-student-session_id').val();
        $.ajax({
            url: `{{ route("sessions.details", ":sessionId") }}`.replace(':sessionId', sessionId),
            type: 'GET',
            success: function(response) {
                if (response.formation) {
                    $('#formation-price').val(response.formation.prix);
                    $('#prix-reel').val(response.formation.prix); // Set Prix Réel to Prix Programme
                    const today = new Date().toISOString().split('T')[0];
                    $('#date-paiement').val(today); // Set Date de Paiement to today's date
                } else {
                    $('#formation-price').val('');
                    $('#prix-reel').val(''); // Clear Prix Réel if no formation data is found
                }
            },
            error: function(xhr, status, error) {
                alert('Erreur lors du chargement des détails de la formation: ' + error);
            }
        });
    }


    window.addEtudiantAndPaiement = function() {
        const etudiantId = $('#etudiant-id').val();
        const sessionId = $('#new-student-session_id').val();
        const datePaiement = $('#date-paiement').val();
        const montantPaye = $('#montant-paye').val();
        const modePaiement = $('#mode-paiement').val();
        const prixReel = $('#prix-reel').val();

        if (!etudiantId || !sessionId) {
            // alert('Etudiant ID or Session ID is missing.');
            alert('Etudiant ID or Session ID is missing.');

            return;
        }

        $.ajax({
            url: `/sessions/${sessionId}/etudiants/${etudiantId}/add`,
            type: 'POST',
            data: {
                etudiant_id: etudiantId,
                date_paiement: datePaiement,
                montant_paye: montantPaye,
                mode_paiement: modePaiement,
                prix_reel: prixReel
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                console.error('Error:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                alert('Erreur lors de l\'ajout de l\'étudiant: ' + xhr.responseText);
            }
        });
    }

    window.openAddPaymentModal = function(etudiantId, sessionId) {
        $.ajax({
            url: `/sessions/${sessionId}/etudiants/${etudiantId}/details`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const resteAPayer = response.reste_a_payer;
                    if (resteAPayer <= 0) {
                        iziToast.warning({
                            message: 'L\'étudiant a déjà payé la totalité de la formation.',
                            position: 'topRight'
                        });
                    } else {
                        $('#etudiant-id').val(etudiantId);
                        $('#session-id').val(sessionId);
                        $('#prix-formation').val(response.prix_formation);
                        $('#prix-reel').val(response.prix_reel);
                        $('#reste-a-payer').val(resteAPayer);
                        $('#paiementAddModal').modal('show');
                    }
                } else {
                    iziToast.error({
                        message: response.error,
                        position: 'topRight'
                    });
                }
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    message: 'Erreur lors de la récupération des détails: ' + error,
                    position: 'topRight'
                });
            }
        });
    };

    // window.addPaiement = function() {
    //     let etudiantId = $('#etudiant-id').val();
    //     let sessionId = $('#session-id').val();
    //     let prixFormation = $('#prix-formation').val();
    //     let prixReel = $('#prix-reel').val();
    //     let nouveauMontantPaye = $('#nouveau-montant-paye').val();
    //     let modePaiement = $('#mode-paiement').val();
    //     let datePaiement = $('#date-paiement').val();

    //     $.ajax({
    //         url: `/sessions/${sessionId}/paiements`,
    //         type: 'POST',
    //         data: {
    //             etudiant_id: etudiantId,
    //             montant_paye: nouveauMontantPaye,
    //             mode_paiement: modePaiement,
    //             date_paiement: datePaiement
    //         },
    //         success: function(response) {
    //             if (response.success) {
    //                 $('#paiementAddModal').modal('hide');
    //                 showContents(sessionId);
    //             } else {
    //                 alert(response.error);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error:', error);
    //             console.error('Status:', status);
    //             console.error('Response:', xhr.responseText);
    //             alert('Erreur lors de l\'ajout du paiement: ' + xhr.responseText);
    //         }
    //     });
    // }

window.addPaiement = function() {
    let etudiantId = $('#etudiant-id').val();
    let sessionId = $('#session-id').val();
    let nouveauMontantPaye = $('#nouveau-montant-paye').val();
    let modePaiement = $('#mode-paiement').val();
    let datePaiement = $('#date-paiement').val();

    // Log the data to check if date_paiement is included
    console.log({
        etudiant_id: etudiantId,
        montant_paye: nouveauMontantPaye,
        mode_paiement: modePaiement,
        date_paiement: datePaiement
    });

    $.ajax({
        url: `/sessions/${sessionId}/paiements`,
        type: 'POST',
        data: {
            etudiant_id: etudiantId,
            montant_paye: nouveauMontantPaye,
            mode_paiement: modePaiement,
            date_paiement: datePaiement  // Ensure this field is included
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
            console.error('Error:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
            alert('Erreur lors de l\'ajout du paiement: ' + xhr.responseText);
        }
    });
}


    window.deleteStudentFromSession = function(etudiantId, sessionId) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cet étudiant de la session ?")) {
            $.ajax({
                url: `/sessions/${sessionId}/etudiants/${etudiantId}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        iziToast.success({
                            message: response.success,
                            position: 'topRight'
                        });
                        showContents(sessionId);
                    } else {
                        iziToast.error({
                            message: response.error,
                            position: 'topRight'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        message: 'Erreur lors de la suppression: ' + error,
                        position: 'topRight'
                    });
                }
            });
        }
    };

    window.showContents = function(sessionId) {
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
                                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#etudiantAddModal" onclick="setSessionId(${sessionId})" data-toggle="tooltip" title="Ajouter un étudiant"><i class="material-icons opacity-10">add</i></button>
                                        <button class="btn btn-secondary" onclick="hideStudentContents()">Fermer</button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-2">
                                    <div class="table-responsive p-0" id="sessions-table">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Nom & Prénom</th>
                                                    <th>Phone</th>
                                                    <th>WhatsApp</th>
                                                    <th>Prix Programme</th>
                                                    <th>Prix Réel</th>
                                                    <th>Montant Payé</th>
                                                    <th>Reste à Payer</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

                if (response.etudiants.length > 0) {
                    response.etudiants.forEach(function(content) {
                        let resteAPayer = content.prix_reel - content.montant_paye;

                        html += `<tr>
                            <td>${content.nomprenom}</td>
                            <td>${content.phone}</td>
                            <td>${content.wtsp}</td>
                            <td>${content.prix_formation}</td>
                            <td>${content.prix_reel}</td>
                            <td>${content.montant_paye}</td>
                            <td>${resteAPayer}</td>
                            <td>
                                <button class="btn btn-dark" onclick="openAddPaymentModal(${content.id}, ${sessionId})"><i class="material-icons opacity-10">payment</i></button>
                                <button class="btn btn-danger" onclick="deleteStudentFromSession(${content.id}, ${sessionId})"><i class="material-icons opacity-10">delete_forever</i></button>
                            </td>
                        </tr>`;
                    });
                } else {
                    html += '<tr><td colspan="10" class="text-center">Aucun étudiant trouvé pour cette Formation.</td></tr>';
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
    };

    window.hideStudentContents = function() {
        $('#formationContentContainer').hide();
        $('html, body').animate({ scrollTop: 0 }, 'slow');
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

});
</script>


<!-- <script type="text/javascript">
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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

    $("#add-new-session").click(function(e){
        e.preventDefault();
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







    window.setSessionId = function(sessionId) {
        $('#new-student-session_id').val(sessionId);
    }

    window.searchStudentByPhone = function() {
        const phone = $('#student-phone-search').val();
        const sessionId = $('#new-student-session_id').val();
        
        if (phone) {
            $.ajax({
                url: '{{ route("etudiant.search") }}',
                type: 'POST',
                data: { phone: phone },
                success: function(response) {
                    if (response.etudiant) {
                        const etudiant = response.etudiant;
                        // Vérifier si l'étudiant est déjà dans la session
                        $.ajax({
                            url: `/sessions/${sessionId}/check-student`,
                            type: 'POST',
                            data: { etudiant_id: etudiant.id },
                            success: function(checkResponse) {
                                if (checkResponse.isInSession) {
                                    $('#student-search-results').html('<div class="alert alert-danger">L\'étudiant est déjà inscrit dans cette session.</div>');
                                } else {
                                    $('#student-search-results').html(
                                        `<div class="alert alert-success">Etudiant trouvé: ${etudiant.nomprenom}</div>
                                        <input type="hidden" id="etudiant-id" value="${etudiant.id}">`
                                    );
                                    loadFormationDetails();
                                    $('#payment-form').show();
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Erreur lors de la vérification de l\'étudiant dans la session: ' + error);
                            }
                        });
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
    }

    window.loadFormationDetails = function() {
        const sessionId = $('#new-student-session_id').val();
        $.ajax({
            url: `{{ route("sessions.details", ":sessionId") }}`.replace(':sessionId', sessionId),
            type: 'GET',
            success: function(response) {
                if (response.formation) {
                    $('#formation-price').val(response.formation.prix);
                } else {
                    $('#formation-price').val('');
                }
            },
            error: function(xhr, status, error) {
                alert('Erreur lors du chargement des détails de la formation: ' + error);
            }
        });
    }

    window.addEtudiantAndPaiement = function() {
        const etudiantId = $('#etudiant-id').val();
        const sessionId = $('#new-student-session_id').val();
        const datePaiement = $('#date-paiement').val();
        const montantPaye = $('#montant-paye').val();
        const modePaiement = $('#mode-paiement').val();
        const prixReel = $('#prix-reel').val();

        if (!etudiantId || !sessionId) {
            alert('Etudiant ID or Session ID is missing.');
            return;
        }

        $.ajax({
            url: `/sessions/${sessionId}/etudiants/${etudiantId}/add`,
            type: 'POST',
            data: {
                etudiant_id: etudiantId,
                date_paiement: datePaiement,
                montant_paye: montantPaye,
                mode_paiement: modePaiement,
                prix_reel: prixReel
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                console.error('Error:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                alert('Erreur lors de l\'ajout de l\'étudiant: ' + xhr.responseText);
            }
        });
    }

    window.openAddPaymentModal = function(etudiantId, sessionId) {
        $.ajax({
            url: `/sessions/${sessionId}/etudiants/${etudiantId}/details`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const resteAPayer = response.reste_a_payer;
                    if (resteAPayer <= 0) {
                        iziToast.warning({
                            message: 'L\'étudiant a déjà payé la totalité de la formation.',
                            position: 'topRight'
                        });
                    } else {
                        $('#etudiant-id').val(etudiantId);
                        $('#session-id').val(sessionId);
                        $('#prix-formation').val(response.prix_formation);
                        $('#prix-reel').val(response.prix_reel);
                        $('#reste-a-payer').val(resteAPayer);
                        $('#paiementAddModal').modal('show');
                    }
                } else {
                    iziToast.error({
                        message: response.error,
                        position: 'topRight'
                    });
                }
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    message: 'Erreur lors de la récupération des détails: ' + error,
                    position: 'topRight'
                });
            }
        });
    };

    window.addPaiement = function() {
        let etudiantId = $('#etudiant-id').val();
        let sessionId = $('#session-id').val();
        let prixFormation = $('#prix-formation').val();
        let prixReel = $('#prix-reel').val();
        let nouveauMontantPaye = $('#nouveau-montant-paye').val();
        let modePaiement = $('#mode-paiement').val();
        let datePaiement = $('#date-paiement').val();

        $.ajax({
            url: `/sessions/${sessionId}/paiements`,
            type: 'POST',
            data: {
                etudiant_id: etudiantId,
                montant_paye: nouveauMontantPaye,
                mode_paiement: modePaiement,
                date_paiement: datePaiement
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
                console.error('Error:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                alert('Erreur lors de l\'ajout du paiement: ' + xhr.responseText);
            }
        });
    }


    window.deleteStudentFromSession = function(etudiantId, sessionId) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cet étudiant de la session ?")) {
                $.ajax({
                    url: `/sessions/${sessionId}/etudiants/${etudiantId}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            iziToast.success({
                                message: response.success,
                                position: 'topRight'
                            });
                            showContents(sessionId); // Recharge les contenus après suppression
                        } else {
                            iziToast.error({
                                message: response.error,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        iziToast.error({
                            message: 'Erreur lors de la suppression: ' + error,
                            position: 'topRight'
                        });
                    }
                });
            }
        };



    window.showContents = function(sessionId) {
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
                                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#etudiantAddModal" onclick="setSessionId(${sessionId})" data-toggle="tooltip" title="Ajouter un étudiant"><i class="material-icons opacity-10">add</i></button>
                                        <button class="btn btn-secondary" onclick="hideStudentContents()">Fermer</button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-2">
                                    <div class="table-responsive p-0" id="sessions-table">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Nom & Prénom</th>
                                                    <th>Phone</th>
                                                    <th>WhatsApp</th>
                                                    <th>Prix Programme</th>
                                                    <th>Prix Réel</th>
                                                    <th>Montant Payé</th>
                                                    <th>Reste à Payer</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

                if (response.etudiants.length > 0) {
                    response.etudiants.forEach(function(content) {
                        let resteAPayer = content.prix_reel - content.montant_paye;

                        html += `<tr>
                            <td>${content.nomprenom}</td>
                            <td>${content.phone}</td>
                            <td>${content.wtsp}</td>
                            <td>${content.prix_formation}</td>
                            <td>${content.prix_reel}</td>
                            <td>${content.montant_paye}</td>
                            <td>${resteAPayer}</td>
                            <td>
                                <button class="btn btn-dark" onclick="openAddPaymentModal(${content.id}, ${sessionId})"><i class="material-icons opacity-10">payment</i></button>
                                <button class="btn btn-danger" onclick="deleteContent(${content.id})"><i class="material-icons opacity-10">delete_forever</i></button>
                            </td>
                        </tr>`;
                    });
                } else {
                    html += '<tr><td colspan="10" class="text-center">Aucun étudiant trouvé pour cette Formation.</td></tr>';
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
    };

    window.hideStudentContents = function() {
        $('#formationContentContainer').hide();
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    };


});
</script> -->


</body>
</html>
