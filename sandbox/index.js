$("#idb-13-12").click(function(){
	$.getJSON( "/stankin/oop/sandbox/idb-13-12/students.json", function( data ) {
	console.log(data);
	if ($("#idb-13-12-table").length){
		$("#idb-13-12-table").slideUp();
		$("#idb-13-12-table").remove();
	}
	else{
		$("#group-13-12").append("<table class=\"group-table\" id=\"idb-13-12-table\" style=\"display: none\"><tr><th>Имя</th><th>М1</th><th>М2</th></tr></table>");
		for (var i = 0; i < data.length; i++) {
			$("#idb-13-12-table").append("<tr><td><a href=\""+data[i].href+"\">"+data[i].name+"</a></td><td>"+data[i].m1+"</td><td>"+data[i].m2+"</td></tr>");
		}
		$("#idb-13-12-table").slideDown();
	}
	});
});
