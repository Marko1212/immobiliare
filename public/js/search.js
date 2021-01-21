
$("#search").keyup(function() {
    let value = $(this).val(); //Valeur saisie
    //console.log(value);

    $.ajax('/api/search/' + value, { type: 'GET' }).then(function(response) {
        console.log(response);
    });
});