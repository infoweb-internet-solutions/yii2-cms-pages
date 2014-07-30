$(function() {
    /**
     * Active toggle
     */
    $(document).on('click', '[data-toggle-active]', function(e){

        e.preventDefault();

        var id = $(this).data('toggle-active');

        $.ajax({
            url: 'active',
            type: 'POST',
            data: {'id': id},
            success: function(result) {

                if (result == 1)
                {
                    // Success
                    $.pjax.reload({container:'#grid-pjax'});
                } else {
                    // Fail
                }
            }
        });
    });
});