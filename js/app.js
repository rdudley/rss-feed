$(document).ready(function() {
	// Variable Declarations ////////////////////////////////////////////////////////////////////////////
	var action = "";
	
	// News Button Click Event //////////////////////////////////////////////////////////////////////////	
	$("#get-news").click(function() {
		$("#data-container").fadeOut("slow", function() {
			$("#search-query").val(""); // clear the search field input
			$("#data-container").empty(); // #data-container is faded out, so empty it
			action = "action=getLatestNews"  // Set action
			getRSS();
		});
	});
	
	// Search Form Submit Event //////////////////////////////////////////////////////////////////////////
	$("#search-form").submit(function(event) {
		event.preventDefault();
		$("#data-container").fadeOut("slow", function() {
			$("#data-container").empty(); // #data-container is faded out, so empty it
			action = "action=search&query=" + $("#search-query").val(); // Set action
			getRSS();
		});
	});
	
	// AJAX Request /////////////////////////////////////////////////////////////////////////////////////	
	function getRSS() {
		
		$.ajax({
   			url: "ajax-handler.php",
			data: action,
			type: "POST",
			error: function(jqXHR, textStatus, errorThrown) {
				$("#data-container").html("<div class=\"alert alert-danger\" role=\"alert\">Something went wrong!</div>").fadeIn("fast");
			},
  			success: function(data){
				$("#data-container").html(data).fadeIn("fast");
   			}
 		});	
	}
	
	// Functions ///////////////////////////////////////////////////////////////////////////////////////
});

