<?php

namespace app\models;

/**
 * Player Model.
 */
class Player extends BaseModel
{
    private $name;

    private $cards;
    private $score;

    public function __construct(string $name = 'Player')
    {
        $this->name = $name;
        $this->score = 0;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getHand()
    {
        $output = '';
        foreach ($this->cards as $key => $card) {
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

    public function addCard(Card $card)
    {
        $this->cards[] = $card;
    }

    public function getHandScore()
    {
        $score = 0;
        $hasAce = false;
        foreach ($this->cards as $card) {
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
        $this->score++;
    }

    public function clearHand()
    {
        $this->score = 0;
        $this->cards = [];
    }
}
