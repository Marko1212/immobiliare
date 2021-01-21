
$("#search").keyup(function() {
    let value = $(this).val(); //Valeur saisie
    //console.log(value);

    $.ajax('/api/search/' + value, { type: 'GET' }).then(function(response) {
        console.log(response);
        let ul = $('<ul></ul>');
        for (let property of response.results) {
            let li = $('<li>'+property.title+'</li>')
            ul.append(li);
        }
        $("#real-estate-list").html(ul);
    });
});