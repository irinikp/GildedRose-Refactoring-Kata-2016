<?php
define("AGED_BRIE", "Aged Brie");
define("BACKSTAGE", "Backstage passes to a TAFKAL80ETC concert");
define("SULFURAS", "Sulfuras, Hand of Ragnaros");

define("MATURE", "Mature");
define("LEGEND", "Legendary");
define("REGULAR", "Regular");
define("CONJURED", "Conjured");

class StringHandler {

    public function wordContains($word, $substring) {
        $pos = strpos($word, $substring);
        if( $pos !== false && $pos >= 0 ) {
            return true;
        }
        return false;
    }
}

class GuildedRoseItem extends Item {

    private $category;
    private $top_quality;
    private $nextDayFetch;
    private const DEFAULT_TOP_QUALITY = 50;
    private const ItemExpressions = array(
            AGED_BRIE => MATURE, 
            BACKSTAGE => MATURE,
            SULFURAS => LEGEND);


    public function __construct($name, $sell_in, $quality) {
        parent::__CONSTRUCT($name, $sell_in, $quality);
        $this->setTopQuality();
        $this->categorizeItem($name);
        $this->createDayFetcher();
    }

    private function setTopQuality() {
        $this->top_quality = self::DEFAULT_TOP_QUALITY;
    }

    private function categorizeItem($name) {
        $stringHandler = new StringHandler();
        $this->category = REGULAR;
        foreach( self::ItemExpressions as $specialExpression => $specialCategory ) {
            if( $stringHandler->wordContains($this->name, $specialExpression) ) {
                $this->category = $specialCategory;
                break;
            }
        }
    }

    private function createDayFetcher() {
        if( $this->category == REGULAR ) {
            $this->nextDayFetch = new NormalNextDayFetcher($this->name, $this->sell_in, $this->quality, $this->top_quality);
        } elseif( $this->category == MATURE ) {
            $this->nextDayFetch = new MatureNextDayFetcher($this->name, $this->sell_in, $this->quality, $this->top_quality);
        } elseif( $this->category == LEGEND ) {
            $this->nextDayFetch = new LegendaryNextDayFetcher($this->name, $this->sell_in, $this->quality, $this->top_quality);
        }
    }

    public function next_day() {
        $this->sell_in = $this->nextDayFetch->next_sellin_day();
        $this->quality = $this->nextDayFetch->next_quality_day();
    }
}

abstract class NextDayFetcher {

    protected $sell_in;
    protected $quality;
    protected $top_quality;
    protected $name;

    public function __construct($name, $sell_in, $quality, $top_quality) {
        $this->name = $name;
        $this->sell_in = $sell_in;
        $this->quality = $quality;
        $this->top_quality = $top_quality;
    }


    public function next_sellin_day() {
        return $this->sell_in;
    }

    public function next_quality_day() {
        return $this->quality;
    }

    protected function decreaseSellin($reductionAmount) {
        $this->sell_in -= $reductionAmount;
    }

    protected function increaseQuality($increaseAmount) {
        $this->quality -= $reductionAmount;
    }

    protected function decreaseQuality($reductionAmount) {
        $this->quality -= $reductionAmount;
    }

    protected function addToQuality($value) {
        $value = $this->adjustQualityValue($value);
        $this->quality += $value;
        $this->fixBottomQuality();
        $this->fixTopQuality();
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
        if( $this->is_conjured($this->name) ) {
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

    private function is_conjured($name) {
        $stringHandler = new StringHandler();
        return $stringHandler->wordContains($name, CONJURED);
    }

    protected function setQuality($value) {
        $this->quality = $value;
    }
}

class NormalNextDayFetcher extends NextDayFetcher {

    public function next_sellin_day() {
        $this->decreaseSellin(1);
        return $this->sell_in;
    }

    public function next_quality_day() {
        $this->addToQuality(-1);
        return $this->quality;
    }
}

class MatureNextDayFetcher extends NextDayFetcher {

    public function next_sellin_day() {
        $this->decreaseSellin(1);
        return $this->sell_in;
    }

    public function next_quality_day() {
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
        return $this->quality;
    }
}

class LegendaryNextDayFetcher extends NextDayFetcher {
}

class GildedRose {

    private $items;

    function __construct($items) {
        $this->items = $items;
    }

    function update_quality() {
        foreach ($this->items as $item) {
            $b = new GuildedRoseItem($item->name, $item->sell_in, $item->quality);
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
