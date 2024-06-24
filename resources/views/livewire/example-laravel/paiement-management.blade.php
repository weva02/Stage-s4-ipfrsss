<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Paiements Management</title>
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
        .modal-content {
            max-width: 800px;
            margin: 0 auto;
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
                        
                        <a href="{{ route('export.paiements') }}" class="btn btn-success">Exporter les Paiements</a>
                    
                    </div>
                    <!-- <div class="me-3 my-3 text-end"></div> -->
                    
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0" id="paiements-table">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom & Prénom</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Portable</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">WhatsApp</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Programme</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Session</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Prix Réel</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant Payé</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mode de Paiement</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Reste à Payer</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date de Paiement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paiements as $paiement)
                                    <tr>
                                        <td>{{ $paiement->id }}</td>
                                        <td>{{ $paiement->etudiant->nomprenom ?? 'N/A'  }}</td>
                                        <td>{{ $paiement->etudiant->phone ?? 'N/A'  }}</td>
                                        <td>{{ $paiement->etudiant->wtsp ?? 'N/A'  }}</td>
                                        <td>{{ $paiement->session->formation->nom ?? 'N/A' }}</td>
                                        <td>{{ $paiement->session->nom ?? 'N/A' }}</td>
                                        <td>{{ $paiement->prix_reel }}</td>
                                        <td>{{ $paiement->montant_paye }}</td>
                                        <td>{{ $paiement->mode->nom ?? 'N/A' }}</td>
                                        <td>{{ $paiement->reste_a_payer }}</td>
                                        <td>{{ $paiement->date_paiement }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $paiements->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
