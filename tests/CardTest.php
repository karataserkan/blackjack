<?php

declare(strict_types = 1);

namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\models\Card;

final class CardTest extends TestCase
{
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
}
