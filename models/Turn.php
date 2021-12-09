<?php

namespace app\models;

use app\helpers\Console;

/**
 * Turn Model.
 */
class Turn extends BaseModel
{
    public Game $game;

    /**
     * @param Game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->clearHands();
        $this->play();
    }

    /**
     * Plays a turn
     * @return mixed
     */
    private function play()
    {
        echo "--------------- New Turn Starts ---------------------------------\n";
        /* Pick cards */
        $this->game->player->addCard($this->getTopCard());
        $this->game->dealer->addCard($this->getTopCard());
        $this->game->player->addCard($this->getTopCard());
        $this->game->dealer->addCard($this->getTopCard(false));

        /* Print hands */
        $this->game->dealer->printHand();
        $this->game->player->printHand();
        echo "---------------------\n";
        if ($this->check()) {
            return $this->end();
        }

        while (true) {
            /* Read from user to hit or stay */
            $playerAction = (int)Console::readFromCli(['1', '2'], $this->game->delay, 'Please enter 1 for Hit or 2 for Stay:');
            if ($playerAction == -1) {
                /* when timeout end game */
                echo "\nGame ended!\n";
                die();
            } elseif ($playerAction == 1) {
                /* hit */

                /* Pick card */
                $this->game->player->addCard($this->getTopCard());
                if ($this->check()) {
                    return $this->end();
                }
                if ($this->game->dealer->getHandScore() < 17) {
                    /* Pick card */
                    $this->game->dealer->addCard($this->getTopCard());
                }
                /* Print hands */
                $this->game->dealer->printHand();
                $this->game->player->printHand();
                echo "---------------------\n";
                if ($this->check()) {
                    return $this->end();
                }
            } elseif ($playerAction == 2) {
                /* stay */
                break;
            }
        }

        $this->end();
    }

    /**
     * Ends the turn
     * @return mixed
     */
    private function end()
    {
        $playerScore = $this->game->player->getHandScore();
        while ($this->game->dealer->getHandScore() < 17) {
            if ($this->check()) {
                break;
            }
            $this->game->dealer->addCard($this->getTopCard());
        }
        $dealerScore = $this->game->dealer->getHandScore();

        /* Open closed cards */
        $this->game->dealer->openCards();

        /* Print hands */
        $this->game->dealer->printHand();
        $this->game->player->printHand();

        /* Select winner */
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
        return;
    }

    /**
     * @param  boolean
     * @return Card
     */
    protected function getTopCard($open = true)
    {
        $card = $this->game->deck->pick();
        if (is_null($card)) {
            echo "Game ended!\n";
            die();
        }
        if ($open) {
            $card->open();
        }
        return $card;
    }

    /**
     * @return void
     */
    public function clearHands()
    {
        $this->game->player->clearHand();
        $this->game->dealer->clearHand();
    }

    /**
     * Check for bust
     * @return bool
     */
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
