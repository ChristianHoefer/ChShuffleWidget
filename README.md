yii-shuffle-widget
==================
Yii Widget wrapper for [Shuffle.js](http://vestride.github.io/Shuffle/)

Example
-------
Copy files to `protected/extensions/ChShuffleWidget` configure it

		'import'=>array(
			'application.models.*',
			'application.components.*',
			'application.extensions.ChShuffleWidget.*',
		),

For displaying Widget copy this to your template

		$dataProvider = ActiveRecord::model()->search();
		$this->Widget('ChShuffleWidget', array(
			'groupSelector'=>'ArField', // which ActiveRecord should be used for filter
			'itemView'=>'shuffleTemplate', // Template for display each item
			'dataProvider'=>$dataProvider,
			'holderId'=>'shuffleExample', // not required but you can use your own id
			'modernizr'=>true, // if you use your own modernizr set it to false
			'debounce'=>true, // not required by Shuffle.js, it works better with it
			'showFilter'=>true, // shows filter Buttons for items
			'options'=>array( // here you cann use all Options from Shuffle.js
				'width'=>250,
				'height'=>100,
				'gutterWidth'=>10
			),
		));

In your shuffleTemplate you cann access each ActiveRecord using $data var.

Ressources
----------
[Shuffle.js GitHub Repo](https://github.com/Vestride/Shuffle)
[Shuffle.js Page](http://vestride.github.io/Shuffle/)