$('#ajax-properties').click(function() {
    $.get('/property.json').then(function(properties) {
        console.log(properties);
    });
});

$('#real_estate_surface').after('<div id="result">' + $('#real_estate_surface').val() + ' m²</div>');

$('#real_estate_surface').on('input', function() {
    //alert('toto');
    $('#result').remove();
    $(this).after('<div id="result">' + $(this).val() + ' m²</div>');
});

// On va corriger l'affichage du label pour l'upload des images

$('[type="file"]').on('change', function() {
    let label = $(this).val().split('\\').pop();
    // on ajoute le label dans l'élément suivant

    $(this).next().text(label);

    let reader = new FileReader();
    reader.readAsDataURL(this.files[0]);

});