$("#idb-13-12").click(function(){
	$.get({url: "idb-13-12/students.json", cache: "false", dataType: "json", success: function( data ) {
		if ($("#idb-13-12-table").length){
			$("#idb-13-12-table").slideUp();
			$("#idb-13-12-table").remove();
		}
		else{
			$("#group-13-12").append("<table class=\"group-table\" id=\"idb-13-12-table\" style=\"display: none\"><tr><th>Имя</th><th>М1</th><th>М2</th></tr></table>");
			$.each(data, function(index, student){
				$("#idb-13-12-table").append("<tr><td><a href=\""+student.href+"\">"+student.name+"</a></td><td>"+student.m1+"</td><td>"+student.m2+"</td></tr>");
			});
			$("#idb-13-12-table").slideDown();
		}
	}});
});
