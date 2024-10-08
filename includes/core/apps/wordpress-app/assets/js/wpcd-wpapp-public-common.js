var ajaxurl=wpcd_wpapp_params.ajaxurl;

/* global ajaxurl */
/* global params */

(function($, params) {

    $(document).ready(function() {
        init();
    });

    function init() {
            
        
        // Resize the public/front-end grid
        function table_grid_view() {

            $('.wpcd-list-table.wpcd-grid-table').each( function() {

                    $(this).removeClass('table-hidden');
                    if( $(this).width() < $(this).data('max-width') ) {
                            $(this).addClass('gridview');
                    } else {
                            $(this).removeClass('gridview');
                    }
            });

        }
        
        $( window ).resize(function() {
                table_grid_view();
        });
        
        // Call the function to resize the public/front-end grid on first load (function defined above.)
        table_grid_view();

        

        // Remove A WordPress Site/App/Server.
        $('body').on('click', '.wpcd_public_row_del_item_action', function(e) {

            e.preventDefault();
            
            var action = $(this).attr('data-wpcd-action');
            var type = $(this).attr('data-wpcd-type');
            var _action = 'wpcd_public_' + type+'_' + action;
            
            var prompt = "";
            var prompr_action_name = action == 'trash' ? 'delete' : action;
            var messages = new Array();
            
            if( 'app' == type ) {
                    messages = JSON.parse( wpcd_public_app_delete_messages );
            } else if( 'server' == type ) {
                    messages = JSON.parse( wpcd_public_server_delete_messages );
            }
            
            prompt = messages[ prompr_action_name ];
            if( !confirm( prompt ) ) {
                    return;
            }
            
            // Setup form data as necessary
            var id = $(this).attr('data-wpcd-id');
            var formData = new FormData();
            formData.append('action', params.action);
            formData.append('_action', _action);
            formData.append('nonce', $(this).attr('data-wpcd-nonce'));
            formData.append('id', id);
            formData.append('params', '');

            // Used for the 'spinner'
            var $lock = $(this).parents('body');
            $lock.lock();

            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {

                        alert(data.data.msg);
                        if ( data.success && data.data.success_reload ) {
                                location.reload();
                        }
                },
                complete: function() {
                                            // An action that threw up the spinner lock screen is complete so unlock it.
                    $lock.unlock();
                },
                error: function(event, xhr, settings, thrownError) {
                    alert('AJAX Error - something went wrong but we cannot tell you what it was.  Its a bummer and illogical I know.  Most likely its a 504 gateway timeout error.  Increase the time your server allows for a script to run to maybe 300 seconds. In the meantime you can check the SSH LOG or COMMAND LOG screens to see if more data was logged there.');
                }
            });

        });

    }

})(jQuery, wpcd_wpapp_params);