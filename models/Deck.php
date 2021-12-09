<?php

namespace app\models;

/**
 * Deck Model.
 */
class Deck extends BaseModel implements \Countable, \Iterator
{
    const TYPES = ['Heart', 'Spade', 'Diamond', 'Club'];
    const VALUES = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];

    private array $_cards = [];
    private int $currentIndex = 0;

    public function __construct(bool $fill = false)
    {
        if ($fill) {
            foreach (static::TYPES as $key => $type) {
                foreach (static::VALUES as $key => $value) {
                    $this->addCard(new Card($type, $value));
                }
            }
        }
    }

    public function pick(int $index = null)
    {
        if (!is_null($index)) {
            if (isset($this->_cards[$index])) {
                return $this->_cards[$index];
            }
            return null;
        }
        if (!$this->valid()) {
            return null;
        }
        $current = $this->current();
        $this->next();
        return $current;
    }

    public function addCard(Card $card)
    {
        $this->_cards[] = $card;
    }

    public function addDeck(Deck $deck)
    {
        foreach ($deck as $key => $card) {
            $this->addCard($card);
        }
    }

    public function count(): int
    {
        return count($this->_cards);
    }

    public function current(): Card
    {
        return $this->_cards[$this->currentIndex];
    }

    public function key(): int
    {
        return $this->currentIndex;
    }

    public function next()
    {
        $this->currentIndex++;
    }

    public function rewind()
    {
        $this->currentIndex = 0;
    }

    public function valid(): bool
    {
        return isset($this->_cards[$this->currentIndex]);
    }

    public function shuffle()
    {
        shuffle($this->_cards);
        $this->rewind();
    }
}
