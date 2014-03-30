<?php

Yii::import('zii.widgets.CListView');

Class ChShuffleWidget extends CListView
{

	/**
	 * @var null - ID for Surrounding element
	 */
	public $holderId = null;

	/**
	 * @var bool - Include modernizr
	 */
	public $modernizr = true;

	/**
	 * @var bool - Include jquery.throttle-debounce.js
	 */
	public $debounce = true;

	/**
	 * @var array - Options for Shuffle.js
	 */
	public $options = array();

	/**
	 * @var null - Data Provider
	 */
	public $dataProvider = null;

	/**
	 * @var null - View Template for this widget
	 */
	public $itemView = null;

	/**
	 * @var bool - Show Shuffle.js filter for elements
	 */
	public $showFilter = true;

	/**
	 * @var null - Selector for filtering via Shuffle.js
	 */
	private $groupSelector = null;

	/**
	 * @var array - Selectors used
	 */
	private $groupSelectorUsed = array();

	/**
	 * Widget init function
	 */
	public function init() {
		// Id from holder div
		if($this->holderId == null)
			$this->holderId = $this->id;

		// set itemSelector
		$this->options = array_merge(array(
			'itemSelector'=>'#'.$this->holderId.' .shuffleItem',
		), $this->options);

	}

	/**
	 * run Widget
	 */
	public function run() {
		// set local groupSelector
		$groupSelector = $this->groupSelector;

		// buffer Output because filter navigation should printed before content
		ob_start();
		// iterate over Data
		foreach($this->dataProvider->getData() as $record){
			// make unique group selector
			$selector = str_replace(' ', '', $record->$groupSelector);

			// save groupSelecor to arr for printing filter navigation
			if(!isset($this->groupSelectorUsed[$selector]))
				$this->groupSelectorUsed[$selector] = $record->$groupSelector;

			// item holder element
			echo CHtml::openTag('div', array('class'=>'shuffleItem', 'data-'.$this->groupSelector=>$selector));

			// render itemView
			$this->render($this->itemView, array('data'=>$record));

			echo CHtml::closeTag('div');
		}
		// get output buffer
		$output = ob_get_clean();

		if($this->showFilter && count($this->groupSelectorUsed) > 0){
			// filter holder element
			$filter = CHtml::openTag('div', array('class'=>'shuffleFilter_'.$this->holderId));

			// filter Reset Button
			$filter .= CHtml::button('All', array('onclick'=>'$(\'#'.$this->holderId.'\').shuffle(\'shuffle\', function($el, shuffle){return true;})'));

			// iterate over used group Selecotrs
			foreach($this->groupSelectorUsed as $key => $value){
				$filter .= CHtml::button($value, array('onclick'=>'$(\'#'.$this->holderId.'\').shuffle(\'shuffle\', function($el, shuffle){return $el.data(\''.$groupSelector.'\') == \''.$key.'\';})'));
			}

			$filter .= CHtml::closeTag('div');
		}

		// print filter navigation
		echo $filter;

		// grid holder element
		echo CHtml::openTag('div', array('id'=>$this->holderId, 'style'=>'position: relative;'));
		// print content
		echo $output;

		echo CHtml::closeTag('div');

		// register scripts
		$this->registerClientScript();
	}

	/**
	 * @var array
	 */
	public $package = array();

	public function registerClientScript() {
		$assetUrl      = Yii::app()->getAssetManager()->publish(dirname(__FILE__).'/assets');

		// Adding Asset base url
		$this->package['baseUrl'] = $assetUrl;

		// Adding Modernizr if needed
		if ($this->modernizr) {
			$this->package['js'][] = 'modernizr.min.js';
		}

		// Adding Throttle-debounce
		if ($this->debounce) {
			$this->package['js'][] = 'jquery.throttle-debounce.js';
		}

		// Adding jQuery Shuffle Plugin
		$this->package['js'][] = YII_DEBUG ? 'jquery.shuffle.js' : 'jquery.shuffle.min.js';

		// Adding Dependencies
		$this->package['depends'][] = 'jquery';

		// Publish Widget Scripts
		$clientScript = Yii::app()->getClientScript();
		$clientScript
			->addPackage('ShuffleWidget', $this->package)
			->registerPackage('ShuffleWidget')
			->registerScript(
				$this->id,
				'$("#'.$this->holderId.'").shuffle('.CJSON::encode($this->options).');',
				CClientScript::POS_READY
			);
	}

}