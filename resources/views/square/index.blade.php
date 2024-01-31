<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://portal.pbbarcouncil.com/public/admin/plugins/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('square/square.css')}}" preload>
    <link rel="stylesheet" href="{{asset('square/app.css')}}" preload>
    <link rel="stylesheet" href="{{asset('square/admin.css')}}" preload>
    {{-- <script src="https://web.squarecdn.com/v1/square.js"></script> --}}
    <script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: #E2E1E0;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 0;
        }

        .stripe_heading {
            font-size: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 25px
        }

        .col_4 {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 30px;
            padding: 0 30%;
            padding-top: 20px;
        }


        .card {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        }

        .bg-gray-100 {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        }

        .container {
            padding: 0 0 50px 0;
        }

        .bg-gray-100 h4 {
            font-size: 50px;
            font-weight: 600;
            /* text-align: center; */
        }

        .bg-gray-100 p {
            color: #AFAFAF;
        }

        .bg-gray-100 table {
            margin-top: 50px;
            width: 80%;
        }

        .bg-gray-100 table td {
            padding-left: 50px;
        }

        .card-header,
        .bg_header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ccc;
        }

        @media screen and (max-width: 770px) {


            .card-header,
            .bg_header {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
        }

        @media screen and (max-width: 770px) {
            .col_4 {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="stripe_heading">
            <h2 class='bg-black text-white p-2' style='border-radius: 5px;'>
                <img src="{{asset('images/square.jpg')}}" style="height: 50px;margin-right: 5px;border-radius:2px;">
            </h2>
        </div>
        <div class="col_4">
            <div class="bg-gray-100">
                <div class='bg_header'>
                    <div>
                        <h4> ${{$package->grand_total}}</h4>
                        <p>{{Carbon\Carbon::now()->format('F d, Y - H:i')}}</p>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="currentColor"
                            class="bi bi-credit-card" viewBox="0 0 16 16">
                            <path
                                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
                            <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z" />
                        </svg>
                    </div>
                </div>
                <table>
                    <tr>
                        <th>To</th>
                        <td><b style="font-size: 18px !important">{{$package->shipTo->fullname ?? ''}}</b></td>
                    </tr>
                    <tr>
                        <th>From</th>
                        <td>{{$package->shipFrom->fullname ?? ''}}</td>
                    </tr>
                    <tr>
                        <th>Service</th>
                        <td>{{$package->service_label}}</td>
                    </tr>
                </table>

                <div style="margin-top: 10px; font-size:12px">
                    I understand and agree to comply with the <a
                        href="https://selfshiplabel.com/terms-and-conditions">terms & conditions</a>.
                </div>
            </div>
            <div class="col_span_3">
                <div class="card card-primary">
                    <div class="card-header">
                        {{-- <h3 class="card-title">Square Payment</h3> --}}
                        <div><img src="{{asset('images/visa-logo.png')}}" style="width: 150px;"></div>
                    </div>
                    <div id="card-container"></div>
                    <div class="card-footer">
                        <button id="card-button" type="button" class="btn btn-primary bg-black"
                            style="border-color: #000;">
                            Pay ${{$package->grand_total}}
                            <span class="spinner-border spinner-border-sm loader" role="status" aria-hidden="true"
                                style="display: none;"></span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- <div class="container">
        <div class="row">
            <div class="text-center" style="margin-top: 50px">
                <img src="http://127.0.0.1:8000/theme/img/logo.png" style="width: 150px;">
                <h3>Please pay ${{$package->grand_total}} to complete your package order.</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div id="payment-status-container"></div>
                <div id="card-container"></div>
                <button id="card-button" type="button">Pay ${{$package->grand_total}}</button>
            </div>
        </div>
    </div> --}}

    <div class="livewire-loader hidden">
        <div class="lds-roller">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="modal-backdrop show backStyle"></div>
    </div>

    <script type="module">
        const payments = Square.payments('sandbox-sq0idb-jeE29DTw_SfJ52vT7ZM7IA', 'L8PVP5B7XVYDR');
        const card = await payments.card();
        await card.attach('#card-container');

        const cardButton = document.getElementById('card-button');
        cardButton.addEventListener('click', async () => {
        const statusContainer = document.getElementById('payment-status-container');

        try {
            const result = await card.tokenize();
            if (result.status === 'OK') {
                $.ajax({
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        'payment_token': result.token,
                        'package_id': '{{$package->id}}',
                    },
                    url: '{{route('package.square-payment.success')}}',
                    beforeSend: function(){
                        $(".livewire-loader").removeClass('hidden');
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.code == 200) {
                            var url = "{{ route('square.complete') }}";
                            location.href = url;
                        } else {
                            alert('PAYMENT ERROR!');
                            $(".livewire-loader").addClass('hidden');
                        }
                    },
                    error : function (errors) {
                        console.log(errors);
                        alert('SYSTEM ERROR!');
                        $(".livewire-loader").addClass('hidden');
                    }
                });

            } else {
            let errorMessage = `Tokenization failed with status: ${result.status}`;
            if (result.errors) {
                errorMessage += ` and errors: ${JSON.stringify(
                result.errors
                )}`;
            }

            throw new Error(errorMessage);
            }
        } catch (e) {
            console.error(e);
            statusContainer.innerHTML = "Payment Failed";
        }
        });
    </script>
</body>