
var game = new Phaser.Game(800, 400, Phaser.CANVAS, 'test', null, true, false);

var BasicGame = function (game) { };

BasicGame.Boot = function (game) {
    // nothing here
};

var isoGroup, water = [],
    cursorPos, cursor, cursorIsoZ,
    player;
BasicGame.Boot.prototype =
{
    preload: function () {
        game.time.advancedTiming = true;
        game.debug.renderShadow = true;
        game.stage.disableVisibilityChange = true;

        game.plugins.add(new Phaser.Plugin.Isometric(game));

        game.load.image('cube', 'assets/tiles/cube.png');
        game.load.atlasJSONHash('tileset', 'assets/tiles/tileset.png', 'assets/tiles/tileset.json');

        game.physics.startSystem(Phaser.Plugin.Isometric.ISOARCADE);
        game.iso.anchor.setTo(0.5, 0.1);
        cursorPos = new Phaser.Plugin.Isometric.Point3();
    },
    create: function () {
        isoGroup = game.add.group();
        game.physics.isoArcade.gravity.setTo(0, 0, -500);

        isoGroup.enableBody = true;
        isoGroup.physicsBodyType = Phaser.Plugin.Isometric.ISOARCADE;

        var tileArray = [];
        tileArray[0] = 'water';
        tileArray[1] = 'sand';
        tileArray[2] = 'grass';
        tileArray[3] = 'stone';
        tileArray[4] = 'wood';
        tileArray[5] = 'watersand';
        tileArray[6] = 'grasssand';
        tileArray[7] = 'sandstone';
        tileArray[8] = 'bush1';
        tileArray[9] = 'bush2';
        tileArray[10] = 'mushroom';
        tileArray[11] = 'wall';
        tileArray[12] = 'window';

        var tiles = [
            9, 2, 1, 1, 4, 4, 1, 6, 2, 10, 2,
            2, 6, 1, 0, 4, 4, 0, 0, 2, 2, 2,
            6, 1, 0, 0, 4, 4, 0, 0, 8, 8, 2,
            0, 0, 0, 0, 4, 4, 0, 0, 0, 9, 2,
            0, 0, 0, 0, 4, 4, 0, 0, 0, 0, 0,
            0, 0, 0, 0, 4, 4, 0, 0, 0, 0, 0,
            0, 0, 0, 0, 4, 4, 0, 0, 0, 0, 0,
            0, 0, 0, 0, 4, 4, 0, 0, 0, 0, 0,
            11, 11, 12, 11, 3, 3, 11, 12, 11, 11, 11,
            3, 7, 3, 3, 3, 3, 3, 3, 7, 3, 3,
            7, 1, 7, 7, 3, 3, 7, 7, 1, 1, 7,
            0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
        ];

        var size = 32;
        var i = 0, tile;
        for (var y = size; y <= game.physics.isoArcade.bounds.frontY - size; y += size) {
            for (var x = size; x <= game.physics.isoArcade.bounds.frontX - size; x += size) {
               
                 tile = game.add.isoSprite(x, y, tileArray[tiles[i]].match("water") ? 0 : 6, 'tileset', tileArray[tiles[i]], isoGroup);
                tile.anchor.set(0.5, 0);
                //tile.isoZ = 6;
                tile.smoothed = false;
                tile.body.moves = false;
                if (tiles[i] === 4) {
                    //ytile.isoZ += 6;
                }
                if (tiles[i] <= 10 && (tiles[i] < 5 || tiles[i] > 6)) {
                   // tile.scale.x = game.rnd.pick([-1, 1]);
                                       // tile.anchor.set(0.5, 0);
                }
                if (tiles[i] === 0) {
                    water.push(tile);
                }
                 if (tiles[i] === 10 || tiles[i] === 9 || tiles[i] === 8) {
                    
                }
                if (tiles[i] === 11 || tiles[i] === 12) {
                    tile.anchor.set(0.5, 0.5);

                }

                
                i++;
            }
        }

        player = game.add.isoSprite(128, 128, 0, 'cube', 0, isoGroup);
        player.tint = 0x86bfda;
        player.anchor.set(0.5,0.5);
        
        game.physics.isoArcade.enable(player);
        player.body.collideWorldBounds = true;

        // Set up our controls.
        this.cursors = game.input.keyboard.createCursorKeys();

      

        var space = game.input.keyboard.addKey(Phaser.Keyboard.SPACEBAR);

        space.onDown.add(function () {
            player.body.velocity.z = 300;
        }, this);
        console.log(player)
        // Make the camera follow the player.
        game.camera.follow(player);
        player.isoZ = 50;
    },
    update: function () {
        water.forEach(function (w) {
            w.isoZ = (-2 * Math.sin((game.time.now + (w.isoX * 7)) * 0.004)) + (-1 * Math.sin((game.time.now + (w.isoY * 8)) * 0.005));
            w.alpha = Phaser.Math.clamp(1 + (w.isoZ * 0.1), 0.2, 1);
        });

        game.iso.unproject(game.input.activePointer.position, cursorPos);
        isoGroup.forEach(function (tile) {
            var inBounds = tile.isoBounds.containsXY(cursorPos.x, cursorPos.y);
         
            if (!tile.selected && inBounds) {
                tile.selected = true;
                tile.tint = 0x86bfda;
                game.add.tween(tile).to({ isoZ:15 }, 200, Phaser.Easing.Quadratic.InOut, true);
            }
            
            else if (tile.selected && !inBounds) {
                tile.selected = false;
                tile.tint = 0xffffff;
                game.add.tween(tile).to({ isoZ:6 }, 200, Phaser.Easing.Quadratic.InOut, true);
            }
        });

       
        var speed = 100;

        if (this.cursors.up.isDown) {
            player.body.velocity.y = -speed;
        }
        else if (this.cursors.down.isDown) {
            player.body.velocity.y = speed;
        }
        else {
            player.body.velocity.y = 0;
        }

        if (this.cursors.left.isDown) {
            player.body.velocity.x = -speed;
        }
        else if (this.cursors.right.isDown) {
            player.body.velocity.x = speed;
        }
        else {
            player.body.velocity.x = 0;
        }

        
        game.physics.isoArcade.collide(isoGroup);
        game.iso.topologicalSort(isoGroup);
    },
    render: function () {
        //isoGroup.forEach(function (tile) {
        //    game.debug.body(tile, 'rgba(189, 221, 235, 0.6)', false);
        //});
        game.debug.text(game.time.fps || '--', 2, 14, "#a7aebe");
        // game.debug.text(Phaser.VERSION, 2, game.world.height - 2, "#ffff00");
    }
};

game.state.add('Boot', BasicGame.Boot);
game.state.start('Boot');