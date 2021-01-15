$('#ajax-properties').click(function() {
    $.get('/property.json').then(function(properties) {
        console.log(properties);
    });
});

