<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - ShippingXPS</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="col-md-8 offset-md-2">
            <img src="https://app.shippingxps.com/theme/img/logo.png" style="width: 200px;" alt="">
            <form id="payment-form" class="mt-4">
                <div id="payment-element"></div>
                <button id="submit" class="btn btn-success">PAY NOW</button>
                <div id="error-message"> </div>
            </form>
        </div>
    </div>

    <script>
        // STRIPE
         const stripe = Stripe('pk_test_51GspqPCGY6Fvdoyj6miHrOjJyckE4Nhs9enKmsXx3INEAE2I2cHUD1JgEYMr38emH08R0SBC6H8tUPNyfZrpxjwP00MJN0z5Ik');                
                const options = {
                    clientSecret: '{{$client_secret}}',
                    appearance: {},
                };
                const elements = stripe.elements(options);
                const paymentElement = elements.create('payment');
                paymentElement.mount('#payment-element');

                // PAYMENT
                const form = document.getElementById('payment-form');
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const { error } = await stripe.confirmPayment({
                        elements,
                        confirmParams: {
                            return_url: 'https://staging.shippingxps.com/stripe-success',
                        },
                    });

                    if (error) {
                        const messageContainer = document.querySelector('#error-message');
                        messageContainer.textContent = error.message;
                    } else {
                    }
                });
    </script>
</body>

</html>