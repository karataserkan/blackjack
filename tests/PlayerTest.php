<?php

declare(strict_types = 1);

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\models\Player;
use app\models\Card;

final class PlayerTest extends TestCase
{
    public function testPlayerName()
    {
        $player = new Player('Dealer');
        $this->assertEquals('Dealer', $player->getName());
    }

    public function testPlayerHandScore()
    {
        $player = new Player();
        $player->addCard(new Card('Heart', '3'));
        $this->assertEquals(3, $player->getHandScore());
        $player->addCard(new Card('Heart', '7'));
        $this->assertEquals(10, $player->getHandScore());
        $player->addCard(new Card('Heart', 'King'));
        $this->assertEquals(20, $player->getHandScore());
        $player->addCard(new Card('Heart', 'Ace'));
        $this->assertEquals(21, $player->getHandScore());
    }
}
