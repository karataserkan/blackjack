<?php

namespace app\models;

/**
 * Game Model.
 */
class Game extends BaseModel
{
    public Deck $deck;
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
        $this->deck = new Deck();
        for ($i=0; $i < $this->getDeckCount(); $i++) {
            $this->deck->addDeck(new Deck(true));
        }
        $this->deck->shuffle();
    }

    public function play()
    {
        while (true) {
            new Turn($this);
        }
    }

    public function endGame()
    {
        echo "Game ended!\n";
        exit(0);
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
