var city = "Podolsk";
var d = new Date();
var n = d.getDate();

$(document).ready(function($) {
	
	var xhr = new XMLHttpRequest();
			xhr.open('GET', 'login.php', false);
			xhr.send();
			if (xhr.status != 200) {
			  alert( xhr.status + ': ' + xhr.statusText );
			} else {
			 city =  xhr.responseText;
	}
	
    $.ajax({
        url: "cache/"+city+'_'+n+'.json',
        dataType: "text",
        success: function(data) {
            var json = $.parseJSON(data);
			console.log(json);
			var tm = (json.temp_min > 0) ? '+'+json.temp_min : json.temp_min;
			var tmx = (json.temp_max > 0) ? '+'+json.temp_max : json.temp_max;

                var string = '<dt><i><img src = "http://openweathermap.org/img/w/'+json.icon+'.png"> '+json.city+'</i> - ' + tm + '..'+tmx+', '+json.desc+', ветер: '+json.wind+' м/с</dt>';
                $(string).appendTo('#wea');
            

        },
		error: function(data){
			 $.ajax({
				url: "parse.php?city="+city,
				dataType: "text",
				success: function(data) {
					var json = $.parseJSON(data);
					console.log(json);
					json.temp_min = (json.temp_min > 0) ? '+'+json.temp_min : json.temp_min;
					json.temp_max = (json.temp_max > 0) ? '+'+json.temp_max : json.temp_max;

						var string = '<dt><i><img src = "http://openweathermap.org/img/w/'+json.icon+'.png"> '+json.city+'</i> - ' + json.temp_min + '..'+json.temp_max+', '+json.desc+', ветер: '+json.wind+' м/с</dt>';
						$(string).appendTo('#wea');
        }
    });
		},
    });
});