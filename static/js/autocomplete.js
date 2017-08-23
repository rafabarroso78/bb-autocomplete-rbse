$(function() {
 
    $("#topic_title").autocomplete({
        source: function( request, response ) {
        $.ajax({
                url: "autocomplete",
                data: {term: request.term},
                dataType: "json",
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label: item.label,
                            value: item.value,
                            id: item.id,
                        }
                    }));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            
            var url = ui.item.id;
            console.log(url);
            if(url != '#') {
                location.href = '/product?id='+url;
            }
        },
 
        html: true, // optional (jquery.ui.autocomplete.html.js required)
 
      // optional (if other layers overlap autocomplete list)
        open: function(event, ui) {
            $(".ui-autocomplete").css("z-index", 1000);
        }
    });

    $("#test-button").on('click', function()
    {
        console.log('Testing button!');
        $.ajax({
              url: 'autocomplete.php',
              type: 'GET',
              success: function(result) {
                console.log('Deleted alerts');
                console.log(result);

              },
              error: function(result) {
                console.log('Failed to delete');
              }
          });
    });
 
});
