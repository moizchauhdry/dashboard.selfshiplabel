<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://portal.pbbarcouncil.com/public/admin/plugins/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('square/square.css')}}" preload>
    <link rel="stylesheet" href="{{asset('square/app.css')}}" preload>
    <link rel="stylesheet" href="{{asset('square/admin.css')}}" preload>
    {{-- <script src="https://web.squarecdn.com/v1/square.js"></script> --}}
    <script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="text-center" style="margin-top: 50px">
                <img src="{{asset('frontend/images/public-paypal.png')}}" style="width: 150px;">
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
    </div>

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
