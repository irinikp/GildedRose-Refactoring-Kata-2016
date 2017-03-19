<?php
use PHPUnit\Framework\TestCase;

require_once '..\src\gilded_rose.php';
//require_once '..\src\gilded_rose2.php';

final class GildedRoseTest extends TestCase {

	// test values before update
    function testNotNull() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $this->assertNotNull($Items[0]);
    }

    function testNotNullName() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $this->assertNotNull($Items[0]->name);
    }

    function testNotNullSellin() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $this->assertNotNull($Items[0]->sell_in);
    }

    function testNotNullQuality() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $this->assertNotNull($Items[0]->quality);
    }

    function testNotNullGildedrose() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $this->assertNotNull($gildedRose);
    }

    function testNameValue() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $this->assertEquals("foo", $Items[0]->name);
    }

    function testSellinValue() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $this->assertEquals(2, $Items[0]->sell_in);
    }

    function testQualityValue() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $this->assertEquals(4, $Items[0]->quality);
    }

    // test standard Items behaviour after update
    function testNotNullAfterUpdate() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $gildedRose->update_quality();
        $this->assertNotNull($Items[0]);
    }

    function testNotNullNameAfterUpdate() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $gildedRose->update_quality();
        $this->assertNotNull($Items[0]->name);
    }

    function testNotNullSellinAfterUpdate() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $gildedRose->update_quality();
        $this->assertNotNull($Items[0]->sell_in);
    }

    function testNotNullQualityAfterUpdate() {
        $Items = array(new Item("foo", 2, 4));
        $gildedRose = new GildedRose($Items);
        $gildedRose->update_quality();
        $this->assertNotNull($Items[0]->quality);
    }

    function testName() {
        $Items = array(new Item("foo", 3, 4));
        $gildedRose = new GildedRose($Items);
        $gildedRose->update_quality();
        $this->assertEquals("foo", $Items[0]->name);
    }

    function testSellinDecreasesByOneAfterOneDay() {
        $Items = array(new Item("foo", 3, 4));
        $gildedRose = new GildedRose($Items);
        $gildedRose->update_quality();
        $this->assertEquals(2, $Items[0]->sell_in);
    }

    function testSellinDecreasesByOneAfterMoreDays() {
        $Items = array(new Item("foo", 10, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(5, $Items[0]->sell_in);
    }

    function testSellinKeepsDecreaseAfterZero() {
        $Items = array(new Item("foo", 5, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<10; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(-5, $Items[0]->sell_in);
    }

    function testQualityDecreasesByOneAfterOneDay() {
        $Items = array(new Item("foo", 3, 4));
        $gildedRose = new GildedRose($Items);
        $gildedRose->update_quality();
        $this->assertEquals(3, $Items[0]->quality);
    }

    function testQualityDecreasesByOneAfterMoreDays() {
        $Items = array(new Item("foo", 10, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(5, $Items[0]->quality);
    }

    function testQualityNeverNegative() {
        $Items = array(new Item("foo", 5, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<10; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(0, $Items[0]->quality);
    }

    function testQualityDecreaseTwiceAfterZeroSellin() {
        $Items = array(new Item("foo", 2, 17));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(9, $Items[0]->quality);
    }

    // test Aged Brie behaviour
    function testAgedBrieSellinDecreasesByOne() {
        $Items = array(new Item("Aged Brie", 10, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(5, $Items[0]->sell_in);
    }

    function testAgedBrieQualityIncreasesByOne() {
        $Items = array(new Item("Aged Brie", 10, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(15, $Items[0]->quality);
    }

    function testAgedBrieQualityNeverMoreThan50() {
        $Items = array(new Item("Aged Brie", 10, 49));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(50, $Items[0]->quality);
    }

    function testAgedBrieSellinKeepsDecreaseAfterZeroSellin() {
        $Items = array(new Item("Aged Brie", 5, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<10; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(-5, $Items[0]->sell_in);
    }

    function testAgedBrieQualityIncreaseTwiceAfterZeroSellin() {
        $Items = array(new Item("Aged Brie", 2, 17));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<7; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(29, $Items[0]->quality);
    }

    // test Sulfuras behaviour
    function testSulfurasNeverHasToBeSold() {
        $Items = array(new Item("Sulfuras, Hand of Ragnaros", 10, 9));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(10, $Items[0]->sell_in);
    }

    function testSulfurasNeveDecreasesQuality() {
        $Items = array(new Item("Sulfuras, Hand of Ragnaros", 10, 9));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
            $gildedRose->update_quality();
        }
        $this->assertEquals(9, $Items[0]->quality);
    }

    // test Backstage behaviour
    function testBackstageSellinDecreasesByOne() {
        $Items = array(new Item("Backstage passes to a TAFKAL80ETC concert", 10, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(5, $Items[0]->sell_in);
    }

    function testBackstageQualityIncreasesByOneWhenSellinMoreThan10Days() {
        $Items = array(new Item("Backstage passes to a TAFKAL80ETC concert", 16, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(15, $Items[0]->quality);
    }

    function testBackstageQualityIncreasesByTwoWhenSellin6To10Days() {
        $Items = array(new Item("Backstage passes to a TAFKAL80ETC concert", 10, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(20, $Items[0]->quality);
    }

    function testBackstageQualityIncreasesByThreeWhenSellinLessThan5Days() {
        $Items = array(new Item("Backstage passes to a TAFKAL80ETC concert", 5, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(25, $Items[0]->quality);
    }

    function testBackstageSellinKeepsDecreaseAfterZero() {
        $Items = array(new Item("Backstage passes to a TAFKAL80ETC concert", 5, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<10; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(-5, $Items[0]->sell_in);
    }

    function testBackstageQualityDropsToZeroAfterConcert() {
        $Items = array(new Item("Backstage passes to a TAFKAL80ETC concert", 2, 20));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<10; $i++ ) {
        	$gildedRose->update_quality();
    	}
        $this->assertEquals(0, $Items[0]->quality);
    }

    // test Conjured Regular behaviour
    function testConjuredSellinDecreasesByOneAfterOneDay() {
        $Items = array(new Item("Conjured foo", 3, 4));
        $gildedRose = new GildedRose($Items);
        $gildedRose->update_quality();
        $this->assertEquals(2, $Items[0]->sell_in);
    }

    function testConjuredSellinDecreasesByOneAfterMoreDays() {
        $Items = array(new Item("Conjured foo", 10, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
            $gildedRose->update_quality();
        }
        $this->assertEquals(5, $Items[0]->sell_in);
    }

    function testConjuredSellinKeepsDecreaseAfterZero() {
        $Items = array(new Item("Conjured foo", 5, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<10; $i++ ) {
            $gildedRose->update_quality();
        }
        $this->assertEquals(-5, $Items[0]->sell_in);
    }

    function testConjuredQualityDecreasesByTwoAfterOneDay() {
        $Items = array(new Item("Conjured foo", 3, 4));
        $gildedRose = new GildedRose($Items);
        $gildedRose->update_quality();
        $this->assertEquals(2, $Items[0]->quality);
    }

    function testConjuredQualityDecreasesByTwoAfterMoreDays() {
        $Items = array(new Item("Conjured foo", 10, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
            $gildedRose->update_quality();
        }
        $this->assertEquals(0, $Items[0]->quality);
    }

    function testConjuredQualityNeverNegative() {
        $Items = array(new Item("Conjured foo", 5, 10));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<10; $i++ ) {
            $gildedRose->update_quality();
        }
        $this->assertEquals(0, $Items[0]->quality);
    }

    function testConjuredQualityDecreaseFourTimesFasterAfterZeroSellin() {
        $Items = array(new Item("Conjured foo", 2, 17));
        $gildedRose = new GildedRose($Items);
        for( $i=0; $i<5; $i++ ) {
            $gildedRose->update_quality();
        }
        $this->assertEquals(1, $Items[0]->quality);
    }
}
