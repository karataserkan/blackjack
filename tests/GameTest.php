<?php

declare(strict_types = 1);

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\models\Game;
use app\models\Card;
use app\models\Player;

final class GameTest extends TestCase
{
    public function testPlayerName()
    {
        $player = new Player('Dealer');
        $this->assertEquals('Dealer', $player->getName());
    }

    public function testCardStatus()
    {
        $card = new Card('Heart', 'Ace');
        $this->assertFalse($card->isOpen());
        $card->open();
        $this->assertTrue($card->isOpen());
    }

    public function testCardValue()
    {
        $card = new Card('Heart', '3');
        $this->assertEquals(3, $card->getValue());
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
