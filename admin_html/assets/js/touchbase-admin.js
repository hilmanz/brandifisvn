$(document).ready(function() {    
	  $(function() {
		$( ".datepicker" ).datepicker({
		  changeMonth: true,
		  changeYear: true,
		  dateFormat:"dd-mm-yy"
		});
	  });
	$(function() {
		$( "#tabs" ).tabs();
	});
});

//tooltip

$(function() {
	$('.tiptip' ).tooltip();
});