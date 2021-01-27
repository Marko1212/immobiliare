var stripe = Stripe("pk_test_51IDuRkKMUaMDTstJv1nCg528B6CUdhDHgMBmMm5kzssKrIdBnc7nYq5MvnPTl6xbjtpUMsoiQjALTKOEMiZ59N0w00m5sL47kv");

//On initialise le formulaire de CB
var elements = stripe.elements();

var card = elements.create("card", {
    style : {
        base: {
            lineHeight: 1.75,
        }
    }
});

card.mount('#stripe-card');

card.on('change', function(event) {
    $('#stripe-pay').attr('disabled', event.empty);
    $('#card-error').text(event.error ? event.error.message : '');
});

$('#stripe-pay').click(function() {
    var clientSecret = $(this).data('client-secret');
    stripe.confirmCardPayment(clientSecret, {
        payment_method: {card: card}
    }).then(function(result) {
        if (result.error) {
            $('#card-error').text(result.error.message);
        } else {
            //Afficher un message de succès quand le paiement a eu lieu
           // alert('Paiment OK');
            window.location = '/cart/success/' + result.paymentIntent.id;
        }

    });
});

