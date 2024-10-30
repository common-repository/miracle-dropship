var $j = jQuery.noConflict();

$j(function() {

	$j(window).load(function() {
		// Animate loader off screen
		$j(".miracle-loader").fadeOut("slow");
		
	});
	
	$j(".miracle-import-btn").click(function(event){
		if($j('input[name="is_import[]"]:checked').length <= 0)
		{
			alert("Please select items to import");
			event.preventDefault();
			return false;
		}

		if(confirm('Are you sure?')){
		$j(".miracle-loader").fadeIn("slow");
		} else {
		event.preventDefault();
		}
	});

	$j("#miracle-select").change(function(event) {
		if(this.checked)
		{
			jQuery(".miracle-item-import").prop('checked', true);
		}
		else
		{
			jQuery(".miracle-item-import").prop('checked', false);
		}
	});
		
});