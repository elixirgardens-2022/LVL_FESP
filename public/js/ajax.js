$(function() {
    // Update database table (AJAX) and update existing record (highlight background color)
    $('#modal_form').submit(function (e) {
        e.preventDefault(); // stop form posting and reloading page
        
        $.ajax({
            url: ajax_url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response)
            {
                update_tr_cells_fnc({"response": response, "fld_names": fld_names});
                $('#modal').hide();
            }
        });
    });
});