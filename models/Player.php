<?php

namespace app\models;

/**
 * Player Model.
 */
class Player extends BaseModel
{
    private $_name;

    private $_cards;
    private $_score;

    /**
     * @param string
     */
    public function __construct(string $name = 'Player')
    {
        $this->_name = $name;
        $this->_score = 0;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->_score;
    }

    /**
     * Returns string representation of cards
     * @return string
     */
    public function getHand()
    {
        $output = '';
        foreach ($this->_cards as $key => $card) {
            $outputLines = [];
            if ($output) {
                $outputLines = explode("\n", $output);
                $cardLines = explode("\n", $card);
                foreach ($outputLines as $key => $line) {
                    $outputLines[$key] .= $cardLines[$key];
                }
                $output = implode("\n", $outputLines);
            } else {
                $output = (string)$card;
            }
        }
        return $output;
    }

    /**
     * Prints hand
     * @return void
     */
    public function printHand()
    {
        echo $this->getName() . " hand:\n". $this->getHand() ."\n";
    }

    /**
     * Adds card to player
     * @param Card
     */
    public function addCard(Card $card)
    {
        $this->_cards[] = $card;
    }

    /**
     * Calculates hand score
     * @return int
     */
    public function getHandScore()
    {
        $score = 0;
        $hasAce = false;
        foreach ($this->_cards as $card) {
            $score += $card->getValue();
            $hasAce = $card->isAce();
        }

        if ($hasAce && $score < 12) {
            $score += 10;
        }
        return $score;
    }

    /**
     * @return void
     */
    public function win()
    {
        $this->_score++;
    }

    /**
     * @return void
     */
    public function clearHand()
    {
        $this->_cards = [];
    }

    /**
     * @return array
     */
    public function getCards()
    {
        return $this->_cards;
    }

    /**
     * Opens all player cards
     * @return void
     */
    public function openCards()
    {
        foreach ($this->_cards as $card) {
            $card->open();
        }
    }
}
