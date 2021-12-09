<?php

declare(strict_types = 1);

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\models\Deck;

final class DeckTest extends TestCase
{
    public function testDeckCardValidity()
    {
        $deck = new Deck(true);
        $deck->shuffle();
        $this->assertTrue($deck->valid());
        $deck->pick();
        $deck->pick();
        $deck->pick();
        $this->assertTrue($deck->valid());
    }
}
