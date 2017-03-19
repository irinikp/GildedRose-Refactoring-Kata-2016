<?php
define("AGED_BRIE", "Aged Brie");
define("BACKSTAGE", "Backstage passes to a TAFKAL80ETC concert");
define("SULFURAS", "Sulfuras, Hand of Ragnaros");

define("MATURE", "Mature");
define("LEGEND", "Legendary");
define("REGULAR", "Regular");
define("CONJURED", "Conjured");

class RegularItem extends Item {
    protected $top_quality;

    public $category;
    public $is_conjured;

    private function contains($word, $index) {
        $pos = strpos($word, $index);
        if( $pos !== false && $pos >= 0 ) {
            return true;
        }
        return false;
    }

    private function decreaseSellin() {
        $this->sell_in -= 1;
    }

    private function setQuality($value) {
        $this->quality = $value;
    }

    private function fixBottomQuality() {
        if( $this->quality < 0 ) {
            $this->setQuality(0);
        }
    }

    private function fixTopQuality() {
        if( $this->quality > $this->top_quality ) {
            $this->setQuality($this->top_quality);
        }
    }

    private function adjustQualityValue($value) {
        if( $this->is_conjured ) {
            if( $value < 0 ) {
                $value *= 2;
            } else {
                $value /= 2;
            }
        }
        if( $this->sell_in < 0 ) {
            $value *= 2;
        }
        return $value;
    }

    private function addToQuality($value) {
        $value = $this->adjustQualityValue($value);
        $this->quality += $value;
        $this->fixBottomQuality();
        $this->fixTopQuality();
    }

    protected function is_mature($name) {
        if( $this->contains($name, AGED_BRIE) ) {
            return true;
        }
        if( $this->contains($name, BACKSTAGE) ) {
            return true;
        }
        return false;
    }

    protected function is_legendary($name) {
        return $this->contains($name, SULFURAS);
    }

    protected function is_conjured($name) {
        return $this->contains($name, CONJURED);
    }

    protected function next_sellin_day() {
        if( $this->category !== LEGEND ) {
            $this->decreaseSellin();
        }
    }

    protected function next_quality_day() {
        switch ($this->category) {
            case REGULAR: 
                $this->addToQuality(-1);
                break;
            case MATURE: 
                $this->addToQuality(1);
                if( $this->name === BACKSTAGE ) {
                    if( $this->sell_in < 10 && $this->sell_in >= 5 ) {
                        $this->addToQuality(1);
                    } elseif( $this->sell_in < 5 && $this->sell_in >= 0 ) {
                        $this->addToQuality(2);
                    } elseif( $this->sell_in < 0 ) {
                        $this->setQuality(0);
                    }
                }
                break;
        }

    }

    function __construct($name, $sell_in, $quality) {
        parent::__CONSTRUCT($name, $sell_in, $quality);
        $this->top_quality = 50;
        $this->is_conjured = $this->is_conjured($name);
        if( $this->is_mature($name) ) {
            $this->category = MATURE;
        } elseif( $this->is_legendary($name) ) {
            $this->category = LEGEND;
        } else {
            $this->category = REGULAR;
        }
    }

    function next_day() {
        $this->next_sellin_day();
        $this->next_quality_day();
    }
}

class GildedRose {

    private $items;

    function __construct($items) {
        $this->items = $items;
    }

    function update_quality() {
        foreach ($this->items as $item) {
            $b = new RegularItem($item->name, $item->sell_in, $item->quality);
            $b->next_day();
            $item->sell_in = $b->sell_in;
            $item->quality = $b->quality;
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
