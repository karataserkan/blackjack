<?php

namespace app\controllers;

use app\models\Game;
use app\models\Card;
use app\models\Player;

/**
 * Game Controller.
 */
class GameController extends BaseController
{
    /**
     * Plays a game
     * @return void
     */
    public function play()
    {
        $playerName = readline('Please enter player name:');
        do {
            $delay = readline('Please enter delay:');
        } while ($delay == 0);
        $player = new Player($playerName);
        $dealer = new Player('Dealer');
        $game = new Game($player, $dealer, $delay);
        $game->play();
    }
}
