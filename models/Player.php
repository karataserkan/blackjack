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

    public function __construct(string $name = 'Player')
    {
        $this->_name = $name;
        $this->_score = 0;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getScore()
    {
        return $this->_score;
    }

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

    public function printHand()
    {
        echo $this->getName() . " hand:\n". $this->getHand() ."\n";
    }

    public function addCard(Card $card)
    {
        $this->_cards[] = $card;
    }

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

    public function win()
    {
        $this->_score++;
    }

    public function clearHand()
    {
        $this->_cards = [];
    }

    public function getCards()
    {
        return $this->_cards;
    }

    public function openCards()
    {
        foreach ($this->_cards as $card) {
            $card->open();
        }
    }
}
