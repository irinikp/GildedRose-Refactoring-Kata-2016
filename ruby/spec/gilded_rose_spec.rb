require_relative "../gilded_rose"
#require_relative "../gilded_rose"
require 'rspec'

describe GildedRose do

  context "item_creation" do
    describe "#testNull" do
      it "is not null" do
        items = [Item.new("foo", 0, 0)]
        expect(items[0]).to be_truthy
      end

      it "does not have a null name" do
        items = [Item.new("foo", 0, 0)]
        expect(items[0].name).to be_truthy
      end

      it "does not have a null sell_in" do
        items = [Item.new("foo", 0, 0)]
        expect(items[0].sell_in).to be_truthy
      end

      it "does not have a null quality" do
        items = [Item.new("foo", 0, 0)]
        expect(items[0].quality).to be_truthy
      end

      it "does not have a null guildedRose instance" do
        items = [Item.new("foo", 0, 0)]
        gildedRose = GildedRose.new(items);
        expect(gildedRose).to be_truthy
      end
    end

    describe "#testInitialValues" do
      it "has the expected name on creation" do
        items = [Item.new("foo", 2, 4)]
        expect(items[0].name).to eq "foo"
      end

      it "has the expected sell_in on creation" do
        items = [Item.new("foo", 2, 4)]
        expect(items[0].sell_in).to eq 2
      end

      it "has the expected quality on creation" do
        items = [Item.new("foo", 2, 4)]
        expect(items[0].quality).to eq 4
      end
    end
  end

  context "standard" do
    it "is not null after update" do
      items = [Item.new("foo", 0, 0)]
      GildedRose.new(items).update_quality()
      expect(items[0]).to be_truthy
    end

    it "does not have a null name after update" do
      items = [Item.new("foo", 0, 0)]
      GildedRose.new(items).update_quality()
      expect(items[0].name).to be_truthy
    end

    it "does not have a null sell_in after update" do
      items = [Item.new("foo", 0, 0)]
      GildedRose.new(items).update_quality()
      expect(items[0].sell_in).to be_truthy
    end

    it "does not have a null quality after update" do
      items = [Item.new("foo", 0, 0)]
      GildedRose.new(items).update_quality()
      expect(items[0].quality).to be_truthy
    end

    it "does not change the name after update" do
      items = [Item.new("foo", 2, 4)]
      GildedRose.new(items).update_quality()
      expect(items[0].name).to eq "foo"
    end

    it "decreases sell_in by 1 after 1 day" do
      items = [Item.new("foo", 3, 4)]
      GildedRose.new(items).update_quality()
      expect(items[0].sell_in).to eq 2
    end
    
    it "decreases sell_in by 1 after several days" do
      items = [Item.new("foo", 10, 10)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].sell_in).to eq 5
    end
    
    it "decreases sell_in by 1 after zero sell_in" do
      items = [Item.new("foo", 5, 10)]
      gd = GildedRose.new(items)
      10.times do
        gd.update_quality()
      end
      expect(items[0].sell_in).to eq -5
    end

    it "decreases quality by 1 after 1 day" do
      items = [Item.new("foo", 3, 4)]
      GildedRose.new(items).update_quality()
      expect(items[0].quality).to eq 3
    end
    
    it "decreases quality by 1 after several days" do
      items = [Item.new("foo", 10, 10)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 5
    end
    
    it "never puts a negative value to quality" do
      items = [Item.new("foo", 5, 10)]
      gd = GildedRose.new(items)
      10.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 0
    end
    
    it "decreases quality by 2 after zero sell_in" do
      items = [Item.new("foo", 2, 17)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 9
    end
  end

  context "aged_brie" do
    it "decreases sell_in by 1" do
      items = [Item.new("Aged Brie", 10, 10)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].sell_in).to eq 5
    end

    it "increases quality by 1" do
      items = [Item.new("Aged Brie", 10, 10)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 15
    end

    it "never increases quality more than 50" do
      items = [Item.new("Aged Brie", 10, 49)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 50
    end

    it "keeps decreasing sell_in after zero sell_in" do
      items = [Item.new("Aged Brie", 5, 10)]
      gd = GildedRose.new(items)
      10.times do
        gd.update_quality()
      end
      expect(items[0].sell_in).to eq -5
    end

    it "increases quality twice as fast after zero sell_in" do
      items = [Item.new("Aged Brie", 2, 17)]
      gd = GildedRose.new(items)
      7.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 29
    end
  end

  context "sulfuras" do
    it "never has to be sold" do
      items = [Item.new("Sulfuras, Hand of Ragnaros", 10, 9)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].sell_in).to eq 10
    end

    it "never decreases quality" do
      items = [Item.new("Sulfuras, Hand of Ragnaros", 10, 9)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 9
    end
  end

  context "backstage" do
    it "decreases sell_in by one" do
      items = [Item.new("Backstage passes to a TAFKAL80ETC concert", 10, 10)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].sell_in).to eq 5
    end

    it "increases quality by 1 when sell_in > 10 days" do
      items = [Item.new("Backstage passes to a TAFKAL80ETC concert", 16, 10)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 15
    end

    it "increases quality by 2 when 5 < sell_in <= 10 days" do
      items = [Item.new("Backstage passes to a TAFKAL80ETC concert", 10, 10)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 20
    end

    it "increases quality by 3 when 0 < sell_in <= 5 days" do
      items = [Item.new("Backstage passes to a TAFKAL80ETC concert", 5, 10)]
      gd = GildedRose.new(items)
      5.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 25
    end

    it "keeps decreasing sell_in after sell_in 0" do
      items = [Item.new("Backstage passes to a TAFKAL80ETC concert", 5, 10)]
      gd = GildedRose.new(items)
      10.times do
        gd.update_quality()
      end
      expect(items[0].sell_in).to eq -5
    end

    it "drops quality to zero after concert" do
      items = [Item.new("Backstage passes to a TAFKAL80ETC concert", 2, 20)]
      gd = GildedRose.new(items)
      10.times do
        gd.update_quality()
      end
      expect(items[0].quality).to eq 0
    end
  end

  context "conjured" do
      it "decreases sell_in by 1 after 1 day" do
        items = [Item.new("Conjured foo", 3, 4)]
        GildedRose.new(items).update_quality()
        expect(items[0].sell_in).to eq 2
      end
      
      it "decreases sell_in by 1 after several days" do
        items = [Item.new("Conjured foo", 10, 10)]
        gd = GildedRose.new(items)
        5.times do
          gd.update_quality()
        end
        expect(items[0].sell_in).to eq 5
      end
      
      it "decreases sell_in by 1 after zero sell_in" do
        items = [Item.new("Conjured foo", 5, 10)]
        gd = GildedRose.new(items)
        10.times do
          gd.update_quality()
        end
        expect(items[0].sell_in).to eq -5
      end

      it "decreases quality by 2 after 1 day" do
        items = [Item.new("Conjured foo", 3, 4)]
        GildedRose.new(items).update_quality()
        expect(items[0].quality).to eq 2
      end
      
      it "decreases quality by 2 after several days" do
        items = [Item.new("Conjured foo", 10, 10)]
        gd = GildedRose.new(items)
        5.times do
          gd.update_quality()
        end
        expect(items[0].quality).to eq 0
      end
      
      it "never puts a negative value to quality" do
        items = [Item.new("Conjured foo", 5, 10)]
        gd = GildedRose.new(items)
        10.times do
          gd.update_quality()
        end
        expect(items[0].quality).to eq 0
      end
      
      it "decreases quality by 4 after zero sell_in" do
        items = [Item.new("Conjured foo", 2, 17)]
        gd = GildedRose.new(items)
        5.times do
          gd.update_quality()
        end
        expect(items[0].quality).to eq 1
      end
  end
end