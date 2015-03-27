jQuery(document).ready(function() {
    runcheck();
	function runcheck() {
        jQuery('.showthumbnail:checked').parent().next().show();
        jQuery('.showthumbnail:not(":checked")').parent().next().hide();

        jQuery('.showthumbnail').change(function() {

            if (jQuery(this).attr('checked'))
                jQuery(this).parent().next().show();
            else
                jQuery(this).parent().next().hide();
        }) 
	}
    jQuery(document).ajaxStop(function() {
        runcheck();
    });
})