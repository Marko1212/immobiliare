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
    // On doit écouter un évenement pour faire quelque chose avec cette image
    reader.addEventListener('load', function(file) {
        // Cleaner les anciennes images (pour ne pas pouvoir afficher
        // plusieurs images)
        $('.custom-file img').remove();

        let base64 = file.target.result;

        let img = $('<img class="img-fluid mt-5 width="250" />');
        img.attr('src', base64);
        // Afficher l'image dans la div .custom-file
        
        $('.custom-file').prepend(img);

    });

    // Le JS va charger l'image en mémoire (code asynchrone)
    reader.readAsDataURL(this.files[0]);

}); // Fin du on('change')