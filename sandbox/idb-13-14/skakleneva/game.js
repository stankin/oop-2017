// класс определяющий параметры игрового прямоугольника и метод для его отрисовки
function rect(color, x, y, width, height) {
    this.color = color;
    this.x = x;
    this.y = y;
    this.width = width;
    this.height = height;
    this.draw = function() 
    {
        context.fillStyle = this.color;
        context.fillRect(this.x, this.y, this.width, this.height);
    };
}

// движение игрока
function playerMove(e) {
    if (start) {
        var x = e.pageX;
        if (player.width / 2 + 10 < x && x < game.width - player.width / 2 - 10) 
        {
            player.x = x - player.width / 2;
        }
    }
}
    
// отрисовка игры
function draw() {
    game.draw(); // рисуем игровое поле
    // рисуем на поле счёт
    context.font = 'bold 64px courier';
    context.textAlign = 'center';
    context.textBaseline = 'top';
    context.fillStyle = '#cbc';
    context.fillText(player.scores+":"+player.lose, 80, 20);
    player.draw(); // правого игрока
    for (var i = 0; i < array.length; i ++)
    {
        array[i].draw();
    }
    for (var i = 0; i < array_v.length; i ++)
    {
        array_v[i].draw();
    }
    if (!start) {
        // вывод статстики
        //context.fillStyle = "#c9c";
        //context.globalAlpha = 0.9;
       // context.fillRect(0, 0, game.width, game.height);
        for (var i=0; i<13; i++)
        {
            for (var j=0; j<20; j++)
            {
                context.globalAlpha = 0.9;
                context.fillStyle = 'rgb(' +Math.floor(220-23.5*i) + ',130,220)';
                context.fillRect(j*25, i*25, 25,25);
            }
        }
        context.font = 'bold 16px courier';
        context.textBaseline = 'top';
        context.fillStyle = '#000';
        context.fillText("Total: " + game.total, game.width / 2, 0);
        context.font = 'bold 70px courier';
        context.textBaseline = 'top';
        context.fillStyle = '#000';
        context.fillText("Strike Game", game.width / 2, game.height / 2 - 50)
        context.font = 'bold 16px courier';
        context.textBaseline = 'top';
        context.fillStyle = '#000';
        context.fillText("click on me", game.width / 2, game.height / 2 + 25);
        context.textBaseline = 'bottom';
        context.fillText("Stankin @ 2017", game.width / 2, game.height);
    }
}
    
// Изменения которые нужно произвести
function update() {

    // Если счёт равен десяти то завершаем партию
    if (player.lose === 10) 
    {
        start = false;
        array=[];
        array_v=[];
        player.scores = 0;
        player.lose = 0;
        game.total++; 
    }

    for (var i = 0; i < array.length; i ++)
    {
        array[i].y-=4;
        if (array[i].y<0) {array.splice(i,1);}
        for (var j = 0; j < array_v.length; j ++)
        {
            if (collision(array[i], array_v[j]))
            {
                array.splice(i,1);
                array_v.splice(j,1);
                player.scores++;
            }
        }
    }

    for (var i = 0; i < array_v.length; i ++)
    {
        array_v[i].y+=4;
        if (array_v[i].y>game.height) {array_v.splice(i,1); player.lose++;}
    }

    count++;
    if (count==50 && start) 
    { 
        count=0; 
        array_v.push(new rect("#f02", Math.random() * (game.width-20), 0, 10, 10));

    }
}
    
function play() 
{
    draw(); // отрисовываем всё на холсте
    update(); // обновляем координаты
}
    
// Инициализация переменных
function init() {
    start = false;
    count = 0;
    // объект который задаёт игровое поле
    array = [];
    array_v = [];
    game = new rect("#000", 0, 0, 480, 320);
    game.total = 0;
    // Ракетки-игроки
    player = new rect("#a0a", game.width/2 - 30, game.height - 10, 10, 10);
    // количество очков
    player.scores = 0;
    player.lose = 0;
    canvas = document.getElementById("strike");
    canvas.width = game.width;
    canvas.height = game.height;
    context = canvas.getContext("2d");
    canvas.onmousemove = playerMove;
    canvas.onclick = startGame;
    setInterval(play, 1000 / 50);

}

function collision(objA, objB) {
    if (objA.x+objA.width  > objB.x &&
        objA.x             < objB.x+objB.width &&
        objA.y+objA.height > objB.y &&
        objA.y             < objB.y+objB.height) 
    {
        return true;
    }
    else 
    {
        return false;
    }
}

function startGame() {
    if (!start) {
        start = true;
        count = 0;
    }
    else 
    {
        array.push(new rect("#70f", player.x, game.height, 10, 10));
    }
}


init();