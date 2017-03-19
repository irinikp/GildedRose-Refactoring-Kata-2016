<?php
define("AGED_BRIE", "Aged Brie");
define("BACKSTAGE", "Backstage passes to a TAFKAL80ETC concert");
define("SULFURAS", "Sulfuras, Hand of Ragnaros");
define("CONJURED", "Conjured");

class GildedRose {

    private $items;

    function __construct($items) {
        $this->items = $items;
    }

    private function contains($word, $index) {
        $pos = strpos($word, $index);
        if( $pos !== false && $pos >= 0 ) {
            return true;
        }
        return false;
    }

    private function is_conjured($item) {
        $pos = strpos($item->name, CONJURED);
        if( $pos !== false && $pos >= 0 ) {
            return true;
        }
        return false;
    }

    private function setQuality($item, $value) {
        $item->quality = $value;
        return $item;
    }

    private function fixZeroQuality($item) {
        if( $item->quality < 0 ) {
            $item = $this->setQuality($item, 0);
        }
        return $item;
    }

    private function fixFiftyQuality($item) {
        if( $item->quality > 50 ) {
            $item = $this->setQuality($item, 50);
        }
        return $item;
    }

    private function adjustQualityValue($item, $value) {
        if( $this->is_conjured($item) ) {
            if( $value < 0 ) {
                $value *= 2;
            } else {
                $value /= 2;
            }
        }
        if($item->sell_in < 0){
            $value *= 2;
        }
        return $value;
    }

    private function addToQuality($item, $value) {
        $value = $this->adjustQualityValue($item, $value);
        $item->quality += $value;
        $item = $this->fixZeroQuality($item);
        $item = $this->fixFiftyQuality($item);
        return $item;
    }

    private function decreaseSellin($item) {
        $item->sell_in -= 1;
        return $item;
    }

    function update_quality() {
        foreach ($this->items as $item) {
            switch ($item->name) {
                case AGED_BRIE: 
                    $item = $this->decreaseSellin($item);
                    $item = $this->addToQuality($item, 1);
                    break;
                case BACKSTAGE: 
                    if( $item->sell_in > 10 ) {
                        $item = $this->addToQuality($item, 1);
                    } elseif( $item->sell_in <= 10 && $item->sell_in > 5 ) {
                        $item = $this->addToQuality($item, 2);
                    } elseif( $item->sell_in <= 5 && $item->sell_in > 0 ) {
                        $item = $this->addToQuality($item, 3);
                    } else {
                        $item = $this->setQuality($item, 0);
                    }
                    $item = $this->decreaseSellin($item);
                    break;
                case SULFURAS: 
                    break;
                default: 
                    $item = $this->decreaseSellin($item);
                    $item = $this->addToQuality($item, -1);
                    break;
            }
        }
    }
}

class Item {

    public $name;
    public $sell_in;
    public $quality;

    function __construct($name, $sell_in, $quality) {
        $this->name = $name;
        $this->sell_in = $sell_in;
        $this->quality = $quality;
    }

    public function __toString() {
        return "{$this->name}, {$this->sell_in}, {$this->quality}";
    }

}
