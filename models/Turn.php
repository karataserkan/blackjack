<?php

namespace app\models;

use app\helpers\Console;

/**
 * Turn Model.
 */
class Turn extends BaseModel
{
    public Game $game;
    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->clearHands();
        $this->play();
    }

    private function play()
    {
        echo "--------------- New Turn Starts ---------------------------------\n";
        $this->game->dealer->addCard($this->getTopCard(false));
        $this->game->player->addCard($this->getTopCard());
        $this->game->dealer->addCard($this->getTopCard());
        $this->game->player->addCard($this->getTopCard());

        echo $this->game->dealer->getName() . " hand:\n". $this->game->dealer->getHand() ."\n";
        echo $this->game->player->getName() . " hand:\n". $this->game->player->getHand() ."\n";
        echo "---------------------\n";
        if ($this->check()) {
            return $this->end();
        }

        do {
            $playerAction = (int)Console::readFromCli(['1', '2'], $this->game->delay, 'Please enter 1 for Hit or 2 for Stay:');
            if ($playerAction === 1) {
                $this->game->player->addCard($this->getTopCard());
                if ($this->check()) {
                    return $this->end();
                }
                if ($this->game->dealer->getHandScore() < 17) {
                    $this->game->dealer->addCard($this->getTopCard());
                }

                echo $this->game->dealer->getName() . " hand:\n". $this->game->dealer->getHand() ."\n";
                echo $this->game->player->getName() . " hand:\n". $this->game->player->getHand() ."\n";
                echo "---------------------\n";
                if ($this->check()) {
                    return $this->end();
                }
            }
        } while ($playerAction != '2');
        $this->end();
    }

    private function end()
    {
        $playerScore = $this->game->player->getHandScore();
        while ($this->game->dealer->getHandScore() < 17) {
            if (!$this->check()) {
                $this->game->dealer->addCard($this->getTopCard());
            }
        }
        $dealerScore = $this->game->dealer->getHandScore();

        $this->game->dealer->openCards();
        echo $this->game->dealer->getName() . " hand:\n". $this->game->dealer->getHand() ."\n";
        echo $this->game->player->getName() . " hand:\n". $this->game->player->getHand() ."\n";
        if ($playerScore > 21) {
            $winner = $this->game->dealer;
        } elseif ($dealerScore > 21) {
            $winner = $this->game->player;
        } elseif ($playerScore > $dealerScore) {
            $winner = $this->game->player;
        } elseif ($playerScore < $dealerScore) {
            $winner = $this->game->dealer;
        }

        if (isset($winner)) {
            $winner->win();
            echo $winner->getName() . " wins. Score is: ".$winner->getHandScore()."\n";
            echo json_encode(["winner" => $winner->getCards()])."\n";
        } else {
            echo json_encode(["draw" => $this->game->player->getCards()])."\n";
        }

        echo "--------------- Turn Ended ---------------------------------\n\n";
    }

    protected function getTopCard($open = true)
    {
        $card = array_shift($this->game->cards);
        if (is_null($card)) {
            echo "Game ended!\n";
            die();
        }
        if ($open) {
            $card->open();
        }
        return $card;
    }

    public function clearHands()
    {
        $this->game->player->clearHand();
        $this->game->dealer->clearHand();
    }

    private function check()
    {
        $dealerScore = $this->game->dealer->getHandScore();
        $playerScore = $this->game->player->getHandScore();
        if ($dealerScore > 20 || $playerScore > 20) {
            return true;
        }

        return false;
    }
}
