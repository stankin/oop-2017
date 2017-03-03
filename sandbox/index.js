$("#idb-13-12").click(function(){
	/*$.getJSON( "students.json", function( data ) {
		console.log(data);
	});*/
	var data = [
		{"name": "Анисимов А.В.", "m1": "", "m2": "", "href": "anisimov"},
		{"name": "Байкова Е.А.", "m1": "", "m2": "", "href": "kbaykova"},
		{"name": "Бобоходжиев Т.Р.", "m1": "", "m2": "", "href": "BTR"},
		{"name": "Будникова Е.В.", "m1": "", "m2": "", "href": "KatyaB"},
		{"name": "Володина К.", "m1": "", "m2": "", "href": "Volodina"},
		{"name": "Гладких Д.", "m1": "", "m2": "", "href": ""},
		{"name": "Горбунов С.Ю.", "m1": "", "m2": "", "href": "Gorbunov"},
		{"name": "Григорьева Е.С.", "m1": "", "m2": "", "href": "Grigoryeva"},
		{"name": "Карапетян Э.К.", "m1": "", "m2": "", "href": "eduard"},
		{"name": "Кочев Д.С.", "m1": "", "m2": "", "href": "dmitry"},
		{"name": "Кузьмин В.", "m1": "", "m2": "", "href": "KuzminVi"},
		{"name": "Лаухтин Т.В.", "m1": "", "m2": "", "href": "Lauhtin"},
		{"name": "Макалкина И.А.", "m1": "", "m2": "", "href": "makalkina"},
		{"name": "Мартиросян Х.М.", "m1": "", "m2": "", "href": "Martirosyan"},
		{"name": "Помазов С.В.", "m1": "", "m2": "", "href": "pomazov"},
		{"name": "Рыбникова Е.", "m1": "", "m2": "", "href": "ribnikovaev"},
		{"name": "Сычева А.А.", "m1": "", "m2": "", "href": "SychevaAA"},
		{"name": "Яковлев Н. Ю.", "m1": "", "m2": "", "href": "yakovlev"}
	]
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