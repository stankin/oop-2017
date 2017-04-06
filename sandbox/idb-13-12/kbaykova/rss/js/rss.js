$(document).ready(function($) {

});

function readRSS (id) {
	var url = ""
	$.ajax({
        url: "get.php?id=" + id,
        dataType: "text",
        success: function(data) {
            var json = $.parseJSON(data);
			url = json.url;
			$("#rss").empty();
			$("#rss").rss(url,
			  {
				limit: 10,
				 dateLocale: 'ru',
				entryTemplate:'<li><a href="{url}">[{author}@{date}] {title}</a><br/>{body}</li>'
			  });
        }
    });
}