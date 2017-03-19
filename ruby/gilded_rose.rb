AGED_BRIE = "Aged Brie"
BACKSTAGE = "Backstage passes to a TAFKAL80ETC concert"
SULFURAS = "Sulfuras, Hand of Ragnaros"

CONJURED = "Conjured"

module Regular
  attr_reader :is_conjured

  def check_conjuration
    @is_conjured = false
    if @name.include? CONJURED
      @is_conjured = true
    end
  end

  def next_day
    check_conjuration
    next_sellin_day
    next_quality_day
  end

  private
    def next_sellin_day
      unless @name.include? SULFURAS
        @sell_in -= 1
      end
    end

    def next_quality_day
      v = adjust_quality_value(-1)
      @quality += v
      fix_bottom_quality
      fix_top_quality
    end

    def adjust_quality_value(v)
      if @is_conjured
        if v < 0
          v *= 2
        else
          v /= 2
        end
      end
      if @sell_in < 0 
        v*2
      else
        v
      end
    end

    def fix_top_quality
      @quality=50 if @quality>50
    end

    def fix_bottom_quality
      @quality=0 if @quality<0
    end
end

class Item
  attr_accessor :name, :sell_in, :quality

  def initialize(name, sell_in, quality)
    @name = name
    @sell_in = sell_in
    @quality = quality
  end

  def to_s()
    "#{@name}, #{@sell_in}, #{@quality}"
  end
end

class RegularItem < Item
  include Regular

  def update_quality
    next_day
  end
end

class MatureItem < Item
  include Regular

  def update_quality
    next_day
    backstage_adjustment
  end

  private
    def next_quality_day
      v = adjust_quality_value(1)
      @quality += v
      fix_bottom_quality
      fix_top_quality
    end

    def backstage_adjustment
      if @name.include? BACKSTAGE
        if (5...10).include? @sell_in
          @quality += 1
        elsif (0...5).include? @sell_in
          @quality += 2
        elsif @sell_in < 0
          @quality = 0
        end
      end
    end
end

class LegendaryItem < Item
  include Regular

  def update_quality
  end
end


class GildedRose

  private
    def sort_item(item)
      category = "RegularItem";
      if item.name.include? AGED_BRIE or item.name.include? BACKSTAGE
        category = "MatureItem";
      elsif item.name.include? SULFURAS
        category = "LegendaryItem"
      end
      category
    end

  public
    def initialize(items)
      @items = items
    end

    def update_quality
      @items.each do |item|
        itemclass = sort_item(item)
        case itemclass
        when "RegularItem"
          b = RegularItem.new(item.name, item.sell_in, item.quality)
        when "MatureItem" 
          b = MatureItem.new(item.name, item.sell_in, item.quality)
        when "LegendaryItem" 
          b = LegendaryItem.new(item.name, item.sell_in, item.quality)
        end
        b.update_quality
        item.sell_in = b.sell_in
        item.quality = b.quality
      end    
    end
end
