	var auth = "";
$(document).ready(function($) {
	var cid = "";
	var cs = "";
	var favs = [];
	var astatus = false;
	
	function in_array(arr,obj) {
		return (arr.indexOf(obj) != -1);
	}
	
    $.ajax({
        url: "data/creds.json",
        dataType: "text",
        success: function(data) {
            var creds = $.parseJSON(data);
			cid = creds.client_id;
			cs = creds.client_secret;
			auth = "?client_id="+cid+"+&client_secret="+cs;
        }
    });
	
	$.ajax({
        url: "worker.php",
        dataType: "text",
        success: function(data) {
            favs = $.parseJSON(data);
			astatus = true;
			document.getElementById("commits").scrollIntoView();
			var string = '<dl><center><a href = "#" onclick = "vkLogout()"><i><i class="fa fa-sign-out" aria-hidden="true"></i></i></a></center></dl>';
			$(string).appendTo('#commits');
        },
		error: function(data) {
			 var string = '<dl><center><a href = "#" onclick = "initVkAuth()"><i><i class="fa fa-vk fa-1x" aria-hidden="true"></i> Войдите через ВК</i></a></center></dl>';
			 $(string).appendTo('#commits');
		}
    });
	
    $.ajax({
        url: "https://api.github.com/repos/stankin/oop/commits" + auth,
        dataType: "text",
        success: function(data) {

            dateTimeReviver = function(key, value) {
                var a;
                if (typeof value === 'string') {
                    a = /\/Date\((\d*)\)\//.exec(value);
                    if (a) {
                        return new Date(+a[1]);
                    }
                }
                return value;
            }
            var json = $.parseJSON(data, dateTimeReviver);



            $.each(json, function(i, val) {
				var star = ""
				
				if (astatus) {
					if (in_array(favs, val.sha)) {
						star = '<span><a class = "star"><i id="commit' + val.sha + '" class="fa fa-star" aria-hidden="true" data-sha="' + val.sha + '" data-act="unfav"></i></a></span>'
					} else {
						star = '<span><a class = "star"><i id="commit' + val.sha + '" class="fa fa-star-o" aria-hidden="true" data-sha="' + val.sha + '" data-act="fav"></i></a></span>'
					}
				}

                var string = '<dt><i><a href = "https://github.com/' + val.author.login + '" target="blank">' + val.commit.author.name + '</a></i> - ' + val.commit.author.email + ' '+ star +'</dt>';
                string += '<dd><a href = "https://github.com/stankin/oop/commit/' + val.sha + '" target = "blank">#' + (val.sha).substr(0, 7) + '</a> - ' + val.commit.message + ' (' + formatDate(new Date(val.commit.author.date)) + ') - <a class = "getc" data-tree="' + val.sha + '">получить файлы из коммита</a><span id="files' + val.sha + '"></span></dd>';
                $(string).appendTo('#commits');
            });

        }
    });
});

function initVkAuth() {
	VK.Auth.login(function(response) {
		if (response.session) {
			location.reload();
		if (response.settings) {
		    location.reload();
		}
	  } else {
		    location.reload();
	  }
	});
}

function vkLogout(){
   VK.Auth.getLoginStatus(function(response){
               if(response.status == 'connected'){
                  VK.Auth.logout(function(response){
                  console.log('Log out VK');
                  document.location.reload();
            });
         }
      });
}

$(document).on('click', '.getc', function(event) {
    var tree = event.target.dataset.tree;
    getCommits(tree);
});

$(document).on('click', '.star', function(event) {
    var sha = event.target.attributes[3].nodeValue;
	var act = event.target.attributes[4].nodeValue;
	console.log(act);
    if (act == "fav") {
		event.target.attributes[4].nodeValue = "unfav";
		$( '#commit' + sha).removeClass( 'fa-star-o' );
		$( '#commit' + sha).toggleClass( 'fa-star' );
	} else {
		event.target.attributes[4].nodeValue = "fav";
		$( '#commit' + sha).toggleClass( 'fa-star-o' );
		$( '#commit' + sha).removeClass( 'fa-star' );
	}
	reactOnFav (sha, act);
});

function reactOnFav (sha, act) {
	$.ajax({
        url: "worker.php?act=" + act + "&hash=" + sha,
        dataType: "text",
        success: function(data) {
            // Nothing, meh
        }
    });
}

function getCommits(commit) {
    $.ajax({
        url: "https://api.github.com/repos/stankin/oop/commits/" + commit + auth,
        dataType: "text",
        success: function(data) {
            var string = '<ul>';
            var json = $.parseJSON(data);
            $.each(json.files, function(i, val) {
                var filename = val.filename.replace(/^.*[\\\/]/, '');
                var path = 'https://raw.githubusercontent.com/stankin/oop/' + commit + '/' + val.filename;
                string += '<li><b>' + filename + '</b> => (SHA1 - ' + val.sha + ') = <a href = "' + path + '" download = "' + filename + '">Скачать</a></li>';
            });
            string += '</ul>';
            $(string).appendTo('#files' + commit)
        }
    });
}

const formatDate = (date) => {
    const myDate = Date.parse(date),
        now = new Date(),
        time = now - myDate;
    if (time < 1000) return 'только что';
    if (time < 60000) return `${Math.floor(time/1000)} сек. назад`;
    if (time < 60000 * 60) return `${Math.floor(time/60000)} мин. назад`;
    return `${date.toLocaleString('ru', {year: '2-digit', month: '2-digit', day: '2-digit'})} ${date.getHours()}:${(date.getMinutes()<10?'0':'') + date.getMinutes()}`;
};
