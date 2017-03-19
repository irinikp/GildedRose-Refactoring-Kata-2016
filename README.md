<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
  	<h1>Gilded Rose Refactoring Kata implementation</h1>
	<div><a href="https://github.com/NotMyself/GildedRose" target="_blank">Original Source in C#</a></div>
	<div><a href="https://github.com/emilybache/GildedRose-Refactoring-Kata" target="_blank/">Translated version I was based upon</a></div>
	<p>
		I have implemented a solution in both PHP and Ruby. Unit testing checks the same cases in both languages. However refactoring is different. 
	</p>
	<h2>Unit testing</h2>
	<h3>Ruby</h3>
	<p>
		Run the <code>$ rspec</code> command on the main directory to execute the tests and receive feedback
	</p>
	<p>
		In order to focus the test feedback on a specific task add <code>"-e ##"</code> to the rspec command
		<ul>
			<li><code>rspec -e item_creation</code> checkes the successful creation of the <code>Item</code> instances</li>
			<li><code>rspec -e standard</code> checkes the successful creation of the <code>GildedRose</code> instances and the expected behaviour of normal items</li>
			<li><code>rspec -e aged_brie</code> checkes the behaviour of Aged Brie</li>
			<li><code>rspec -e sulfuras</code> checkes the behaviour of Sulfuras, Hand of Ragnaros</li>
			<li><code>rspec -e backstage</code> checkes the behaviour of Backstage passes to a TAFKAL80ETC concert</li>
			<li><code>rspec -e conjured</code> checkes the behaviour of conjured items. </li>
		</ul>
	</p>
	<h3>PHP</h3>
	<p>
		I implemented the same tests as in Ruby. Run the command <code>phpunit gilded_rose_test.php</code> under the test directory. <br/>
		In order to choose which refactoring implementation you wish to check, uncomment line 4 or 5 on file <a href="https://github.com/irinikp/GildedRose-Refactoring-Kata/blob/master/php/test/gilded_rose_test.php">gilded_rose_test.php</a>. 
	</p>
	<h2>Refactoring</h2>
	<h3>Ruby</h2>
	<p>
		All standard functions are implemented in the module <code>Regular</code>. I extended the Item Class to 3 more classes (<code>RegularItem</code>, <code>MatureItem</code> and <code>LegendaryItem</code>). I use these classes in the <code>update_quality</code> function of <code>GildedRose</code>
	</p>
	<p>
		I decided that Aged Brie and Backstage both belong in the Mature class, since their behaviour is similar. 
	</p>
	<p>
		The Conjured ability is implemented in the module, as it is common for all items. 
	</p>
	<h3>PHP</h2>
	<p>
		Here rises the question of when does one stop refactoring. In my first implementation <a href="https://github.com/irinikp/GildedRose-Refactoring-Kata/blob/master/php/src/gilded_rose.php">gilded_rose.php</a> I made a refactoring of the <code>GildedRose</code> class which would be my personal choice for a PHP implementation. I created simple functions for each action, easily readable so anyone could be able to edit them if necessary. 
	</p>
	<p>
	However I have also implemented a second refactoring on <a href="https://github.com/irinikp/GildedRose-Refactoring-Kata/blob/master/php/src/gilded_rose2.php">gilded_rose2.php</a> that I would choose if I suspected that the project needed to be flexible for many future extensions. I extended the <code>Item</code> class and added 2 more variables <code>category</code> (values <code>"regular"</code>, <code>"mature"</code> or <code>"legendary"</code>) and <code>is_conjured</code> 
	</p>
	<h2>Comments</h2>
	<p>
		In the initial code I was based on, the requirement <i>"Sulfuras" is a legendary item and as such its Quality is 80 and it never alters</i> was not implemented, and I haven't changed my implementation to support it yet. 
	</p>
	<p>
		The requirements don't describe what happens if a special item (p.ex. Aged Brie) is Conjured, so I took the initiative to delay their quantity improvement. 
	</p>
  </body>
</html>
