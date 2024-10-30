jQuery(document).ready(function($) {
	$("#accord").accordion({autoHeight: false, navigation: true});
	
	$(".use").sortable({placeholder: "ui-state-highlight", connectWith: "ul"});
	$(".dontUse").sortable({placeholder: "ui-state-highlight", connectWith: "ul"});
	$(".use, .dont").disableSelection();
	
	$('#update_list').click(function() {
		var useList = [];
		$("ul.use li").each(function() { 
			useList.push($(this).text());
		});
		var useList = useList.join(',');
		
		var badList = [];
		$("ul.dontUse li").each(function() { 
			badList.push($(this).text());
		});
		var badList = badList.join(',');

		var allList = useList + "|" + badList;
		alert(allList);
		//*
		$.ajax({
			type: "POST",
			url: '../wp-content/plugins/ie-pinned/admin.php',
			data: "job=list&arr=" + allList,
			success: function(msg) {
				//alert(msg);
				$('#update1').text(msg);
				useList = null;
				badList = null;
				allList = null;
			}
		});
		//*/
	});
});