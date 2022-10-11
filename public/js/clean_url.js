$(function() {
    // Stop [input type="submit"] data being appended to URL
    // eg. '&update_limit=Limit' or '&goto=Page'
    // *****************************************************
    $("form").submit(function() {
        for (let i = 0; i < clean_urls.length; i++) {
            $(this).children('[name="' + clean_urls[i] + '"]').remove();
        }
    });
});