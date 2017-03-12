var auth = "?client_id=4614cd3accaefacf4694&client_secret=5dff49f531f6fecdaec3945ab7c5e94b115211b2";

$(document).ready(function($) {
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
                var string = '<dt><i><a href = "https://github.com/' + val.commit.author.name + '" target="blank">' + val.commit.author.name + '</a></i> - ' + val.commit.author.email + '</dt>';
                string += '<dd><a href = "https://github.com/stankin/oop/commit/' + val.sha + '" target = "blank">#' + (val.sha).substr(0, 7) + '</a> - ' + val.commit.message + ' (' + formatDate(new Date(val.commit.author.date)) + ') - <a class = "getc" data-tree="' + val.sha + '">получить файлы из коммита</a><span id="files' + val.sha + '"></span></dd>';
                $(string).appendTo('#commits');
            });

        }
    });
});


$(document).on('click', '.getc', function(event) {
    var tree = event.target.dataset.tree;
    getCommits(tree);
});

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
