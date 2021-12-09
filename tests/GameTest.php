<?php

declare(strict_types = 1);

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\models\Game;
use app\models\Player;
use app\models\Turn;

final class GameTest extends TestCase
{
    protected Game $game;

    protected function setUp()
    {
        $player = new Player();
        $dealer = new Player('Dealer');
        $this->game = new Game($player, $dealer, 1);
    }

    public function testDeckCount()
    {
        $this->assertEquals(312, $this->game->deck->count());
    }

    //Tests will continue here...
}
