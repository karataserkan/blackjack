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
     * Play game.
     *
     */
    public function play()
    {
        /*$playerName = readline('Please enter player name:');
        do {
            $delay = readline('Please enter delay:');
        } while ($delay == 0);*/
        $playerName = 'asd';
        $delay =100;
        $player = new Player($playerName);
        $dealer = new Player('Dealer');
        $game = new Game($player, $dealer, $delay);
        $game->play();
    }
}
