(function($) {
    $(document).ready(function (){
            	
            	/* Make some options sortable */
               gridlove_opt_sortable();

                 $(document).on('widget-added', function(e){
                 	gridlove_opt_sortable();
                 	 

                 });

                 $(document).on('widget-updated', function(e){
                 	gridlove_opt_sortable();

                 });


                function gridlove_opt_sortable(){
                	$( ".gridlove-widget-content-sortable" ).sortable({
                  	revert: false,
                  	cursor: "move"
                	});
                }
			

    });
    
})(jQuery);