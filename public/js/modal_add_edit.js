$(function() {
    // Display modal add/edit product box
    $('a[data-id]').on('click', function () {
        let id = $(this).attr('data-id');
        let $rowId = $('tr[data-id="' + id + '"]');
        
        // Add ID to hidden form field
        if ('none' != id) {
            $('.display-id', $('#modal')).html('ID: ' + id);
        }
        
        // Populate text fields with data from existing row cells
        $('input[name=id]').val(id);
        for (let i = 0; i < fld_names.length; i++) {
            let fld_val = $('.' + fld_names[i],  $rowId).text();
            
            $('input[name=input_' + fld_names[i] + '_orig]', $('#modal')).val(fld_val);
            $('input[name=input_' + fld_names[i] + ']', $('#modal')).val(fld_val);
        }
        
        $('#modal').show();
    });
    
    // Close modal when 'X' clicked
    $('#modal .modal-close-button').on('click', function () {
        $('#modal').hide();
    });
    
    // Close modal when escape key is pressed
    $(document).on('keyup',function(e){
        if (e.keyCode === 27) { // esc
            $('#modal').hide();
        }
    });
});