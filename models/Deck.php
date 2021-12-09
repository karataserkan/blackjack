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

    /**
     * @param bool|boolean
     */
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

    /**
     * Picks a card from deck
     * @param  int|null
     * @return null|Card
     */
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

    /**
     * Adds card to deck
     * @param Card
     */
    public function addCard(Card $card)
    {
        $this->_cards[] = $card;
    }

    /**
     * Adds deck cards to deck
     * @param Deck
     */
    public function addDeck(Deck $deck)
    {
        foreach ($deck as $key => $card) {
            $this->addCard($card);
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->_cards);
    }

    /**
     * @return Card
     */
    public function current(): Card
    {
        return $this->_cards[$this->currentIndex];
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->currentIndex;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->currentIndex++;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->currentIndex = 0;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->_cards[$this->currentIndex]);
    }

    /**
     * Shuffles cards
     * @return void
     */
    public function shuffle()
    {
        shuffle($this->_cards);
        $this->rewind();
    }
}
