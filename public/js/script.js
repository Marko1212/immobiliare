$('#ajax-properties').click(function() {
    $.get('/property.json').then(function(properties) {
        console.log(properties);
    });
});

$('#result').remove();
$('#real_estate_surface').after('<div id="result">' + $('#real_estate_surface').val() + ' m²</div>');

$('#real_estate_surface').on('input', function() {
    //alert('toto');
    $('#result').remove();
    $(this).after('<div id="result">' + $(this).val() + ' m²</div>');
});