<?php

namespace app\models;

use app\helpers\Console;

/**
 * Game Model.
 */
class Game extends BaseModel
{
    protected $cards;
    public $player;
    public $dealer;
    public $delay;

    public function __construct(Player $player, Player $dealer, int $delay)
    {
        $this->player = $player;
        $this->dealer = $dealer;
        $this->delay = $delay;
        $this->prepareCards();
    }

    private function prepareCards()
    {
        $this->cards = [];
        for ($i=0; $i < $this->getDeckCount(); $i++) {
            $this->cards = array_merge($this->cards, Card::getRandomDeck());
        }
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function play()
    {
        while (true) {
            $this->playARound();
        }
    }

    public function endGame()
    {
        echo "Game ended!\n";
        exit(0);
    }

    public function playARound()
    {
        echo "--------------- New Turn Starts ---------------------------------\n";
        $this->clearHands();
        $this->dealer->addCard($this->getTopCard(false));
        $this->player->addCard($this->getTopCard());
        $this->dealer->addCard($this->getTopCard());
        $this->player->addCard($this->getTopCard());

        echo $this->player->getName() . " hand:\n". $this->player->getHand(true) ."\n";
        echo $this->dealer->getName() . " hand:\n". $this->dealer->getHand() ."\n";

        do {
            $playerAction = (int)Console::readFromCli(['1', '2'], $this->delay, 'Please enter 1 for Hit or 2 for Stay:');
            if ($playerAction === 1) {
                $this->player->addCard($this->getTopCard());
                if ($this->dealer->getHandScore() < 17) {
                    $this->dealer->addCard($this->getTopCard());
                }

                echo $this->player->getName() . " hand:\n". $this->player->getHand(true) ."\n";
                echo $this->dealer->getName() . " hand:\n". $this->dealer->getHand() ."\n";
                if ($this->dealer->getHandScore() > 21) {
                    $this->endRound();
                    break;
                }
            }
        } while ($playerAction != '2');
        $this->endRound();
    }

    public function endRound()
    {
        $playerScore = $this->player->getHandScore();

        while ($this->dealer->getHandScore() < 17) {
            $this->dealer->addCard($this->getTopCard());
        }
        $dealerScore = $this->dealer->getHandScore();

        echo $this->player->getName() . " hand:\n". $this->player->getHand(true) ."\n";
        echo $this->dealer->getName() . " hand:\n". $this->dealer->getHand(true) ."\n";
        if ($dealerScore > 21) {
            $this->player->win();
            echo $this->player->getName() . " wins.\n";
            echo json_encode(["winner" => $this->player->getHand(true)])."\n";
        } elseif ($playerScore > 21) {
            $this->dealer->win();
            echo $this->dealer->getName() . " wins.\n";
            echo json_encode(["winner" => $this->dealer->getHand(true)])."\n";
        } elseif ($playerScore == 21 && $dealerScore == 21) {
            echo json_encode(["draw" => $this->player->getHand(true)])."\n";
        } elseif ($playerScore > $dealerScore == 21) {
            echo json_encode(["draw" => $this->player->getHand(true)])."\n";
        }

        echo "--------------- Turn Ended ---------------------------------\n\n";
    }

    public function clearHands()
    {
        $this->player->clearHand();
        $this->dealer->clearHand();
    }

    protected function getTopCard($open = true)
    {
        $card = array_shift($this->cards);
        if (is_null($card)) {
            $this->endGame();
        }
        if ($open) {
            $card->open();
        }
        return $card;
    }

    private function getDeckCount()
    {
        $config = $GLOBALS['config'];
        if (isset($config['deck'])) {
            return $config['deck'];
        }

        return 1;
    }
}
