
        <script src="js/jquery-2.0.3.min.js" type="text/javascript"></script>
        <script src="phaser/phaser.2.6.2.min.js" type="text/javascript"></script>
        <script src="phaser/blob.js" type="text/javascript"></script>
        <script src="phaser/canvas-to-blob.js" type="text/javascript"></script>
        <script src="phaser/filesaver.js" type="text/javascript"></script>
        <script src="phaser/embed.js" type="text/javascript"></script>

        <style>
            body {
                margin: 0px;
                padding: 0px;
                font-family: Arial;
                font-size: 14px;
                background-color: #ff4377;
                color: #fff;
            }
        </style>

    <body>
        <div id="phaser-example"></div>

        <script type="text/javascript">

        var IDE_HOOK = false;
        var VERSION = '2.6.2';

  

var game = new Phaser.Game(800, 600, Phaser.CANVAS, 'phaser-example', { preload: preload, create: create, update: update, render: render });

function preload() {

    game.load.spritesheet('item', 'number-buttons.png', 160, 160);
}

var simon;
var N = 1;
var userCount = 0;
var currentCount = 0;
var sequenceCount = 16;
var sequenceList = [];
var simonSez = false;
var timeCheck;
var litSquare;
var winner;
var loser;
var intro;

function create() {

    simon = game.add.group();
    var item;

    for (var i = 0; i < 3; i++)
    {
        item = simon.create(150 + 168 * i, 150, 'item', i);
        // Enable input.
        item.inputEnabled = true;
        item.input.start(0, true);
        item.events.onInputDown.add(select);
        item.events.onInputUp.add(release);
        item.events.onInputOut.add(moveOff);
        simon.getAt(i).alpha = 0;
    }

    for (var i = 0; i < 3; i++)
    {
        item = simon.create(150 + 168 * i, 318, 'item', i + 3);
        // Enable input.
        item.inputEnabled = true;
        item.input.start(0, true);
        item.events.onInputDown.add(select);
        item.events.onInputUp.add(release);
        item.events.onInputOut.add(moveOff);
        simon.getAt(i + 3).alpha = 0;
    }

    introTween();
    setUp();
    setTimeout(function(){simonSequence(); intro = false;}, 6000);

}

function restart() {

    N = 1;
    userCount = 0;
    currentCount = 0;
    sequenceList = [];
    winner = false;
    loser = false;
    introTween();
    setUp();
    setTimeout(function(){simonSequence(); intro=false;}, 6000);

}

function introTween() {

    intro = true;

    for (var i = 0; i < 6; i++)
    {
        var flashing = game.add.tween(simon.getAt(i)).to( { alpha: 1 }, 500, Phaser.Easing.Linear.None, true, 0, 4, true);
        var final = game.add.tween(simon.getAt(i)).to( { alpha: .25 }, 500, Phaser.Easing.Linear.None, true);

        flashing.chain(final);
        flashing.start();
    }

}

function update() {

    if (simonSez)
    {
        if (game.time.now - timeCheck >700-N*40)
        {
            simon.getAt(litSquare).alpha = .25;
            game.paused = true;

            setTimeout(function()
            {
                if ( currentCount< N)
                {
                    game.paused = false;
                    simonSequence();
                }
                else
                {
                    simonSez = false;
                    game.paused = false;
                }
            }, 400 - N * 20);
        }
    }
}

function playerSequence(selected) {

    correctSquare = sequenceList[userCount];
    userCount++;
    thisSquare = simon.getIndex(selected);

    if (thisSquare == correctSquare)
    {
        if (userCount == N)
        {
            if (N == sequenceCount)
            {
                winner = true;
                setTimeout(function(){restart();}, 3000);
            }
            else
            {
                userCount = 0;
                currentCount = 0;
                N++;
                simonSez = true;
            }
        }
    }
    else
    {
        loser = true;
        setTimeout(function(){restart();}, 3000);
    }

}

function simonSequence () {

    simonSez = true;
    litSquare = sequenceList[currentCount];
    simon.getAt(litSquare).alpha = 1;
    timeCheck = game.time.now;
    currentCount++;

}

function setUp() {

    for (var i = 0; i < sequenceCount; i++)
    {
        thisSquare = game.rnd.integerInRange(0,5);
        sequenceList.push(thisSquare);
    }

}

function select(item, pointer) {

    if (!simonSez && !intro && !loser && !winner)
    {
        item.alpha = 1;
    }

}

function release(item, pointer) {

    if (!simonSez && !intro && !loser && !winner)
    {
        item.alpha = .25;
        playerSequence(item);
    }
}

function moveOff(item, pointer) {

    if (!simonSez && !intro && !loser && !winner)
    {
        item.alpha = .25;
    }

}

function render() {

    if (!intro)
    {
        if (simonSez)
        {
            game.debug.text('Запоминайте', 360, 96, 'rgb(255,67,119)');
        }
        else
        {
            game.debug.text('Ваш ход', 360, 96, 'rgb(1,255,180)');
        }
    }
    else
    {
        game.debug.text('Приготовьтесь', 360, 96, 'rgb(255,255,87)');
    }

    if (winner)
    {
        game.debug.text('Победа!', 360, 32, 'rgb(1,255,180)');
    }
    else if (loser)
    {
        game.debug.text('Поражение!', 360, 32, 'rgb(255,67,119)');
    }

}
        
        </script>

    

</body></html>