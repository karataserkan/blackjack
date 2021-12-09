<?php

namespace app\models;

/**
 * Card Model.
 */
class Card extends BaseModel
{
    public $type;
    public $value;
    private bool $_open;

    /**
     * @param string
     * @param string
     * @param bool|null
     */
    public function __construct(string $type, string $value, bool $open = null)
    {
        $this->type = $type;
        $this->value = $value;
        if (is_null($open)) {
            $open = false;
        }
        $this->_open = $open;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns card value
     * @return int
     */
    public function getValue()
    {
        if (in_array($this->value, ['Jack', 'Queen', 'King'])) {
            return 10;
        } elseif ($this->value == 'Ace') {
            return 1;
        }

        return (int)$this->value;
    }

    /**
     * Returns whether card is Ace or not
     * @return boolean
     */
    public function isAce()
    {
        return $this->value == 'Ace';
    }

    /**
     * Returns whether card is open or not
     * @return boolean
     */
    public function isOpen()
    {
        return $this->_open == true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $notation = '?';
        $type = '?';
        if ($this->isOpen()) {
            $notation = $this->value;
            if (strlen($notation) > 2) {
                $notation = $notation[0];
            }
            $type = $this->type;
        }
        $notation = str_pad($notation, 2, ' ');
        $type = str_pad($type, 9, ' ');
        return "|-------|\n".
        "|$notation     |\n".
        "|       |\n".
        "|     $notation|\n".
        "|-------|\n".
        "$type\n";
        return $this->type.'-'.$this->value;
    }

    /**
     * Opens card
     * @return void
     */
    public function open()
    {
        $this->_open = true;
    }

    /**
     * Closes card
     * @return void
     */
    public function close()
    {
        $this->_open = false;
    }
}
