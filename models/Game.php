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

    /**
     * @param Player
     * @param Player
     * @param int
     */
    public function __construct(Player $player, Player $dealer, int $delay)
    {
        $this->player = $player;
        $this->dealer = $dealer;
        $this->delay = $delay;
        $this->prepareCards();
    }

    /**
     * Prepare cards to start a game
     * @return void
     */
    private function prepareCards()
    {
        $this->deck = new Deck();
        for ($i=0; $i < $this->getDeckCount(); $i++) {
            $this->deck->addDeck(new Deck(true));
        }
        $this->deck->shuffle();
    }

    /**
     * Plays a game
     * @return void
     */
    public function play()
    {
        while (true) {
            new Turn($this);
        }
    }

    /**
     * Ends game
     * @return void
     */
    public function endGame()
    {
        echo "Game ended!\n";
        exit(0);
    }

    /**
     * Returns deck count
     * @return int
     */
    private function getDeckCount()
    {
        $config = $GLOBALS['config'];
        if (isset($config['deck'])) {
            return $config['deck'];
        }

        return 1;
    }
}
