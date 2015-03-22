<?php

/**
 * Component you must load to used ReCaptcha.
 */
class RecaptchaComponent extends Component{

	// Used to keep the controller
	public $controller = null;
	// Default settings
	protected $_settings = array(
		'theme'=>'light',
		'type'=>'image');
	// The API secret key
	private $_secret_key = '';
	// The API site key
	private $_site_key = '';

	/**
	 * Component constructor.
	 */
	public function __construct(ComponentCollection $collection, $settings = array()){
		parent::__construct($collection, $settings);
		$this->controller = $collection->getController();
		$this->_settings = array_merge( $this->_settings, $settings);
	}

	/**
	 * Overload initialize component function to load automaticaly Recaptcha helper and behavior.
	 */
	public function initialize(Controller $controller, $settings = array()){
		$this->controller = $controller;
		$this->_secret_key = Configure::read('Recaptcha.secret_key');
		$this->_site_key = Configure::read('Recaptcha.site_key');

		if(!isset($this->controller->helpers['Recaptcha.Recaptcha'])){ // Autoload Recaptcha helper
			$this->controller->helpers[] = 'Recaptcha.Recaptcha';
		}
		$this->controller->{$this->controller->modelClass}->Behaviors->load('Recaptcha.Recaptcha',array('secret_key'=>$this->_secret_key)); // Autoload Recaptcha behavior

		$this->controller->helpers['Recaptcha.Recaptcha']=array(
			'site_key'=>$this->_site_key,
			'theme'=>$this->_settings['theme'],
			'type'=>$this->_settings['type']);

		if(empty($this->_secret_key) || empty($this->_site_key)){
			throw new InternalErrorException(__d('recaptcha','Your plugin Recaptcha isn\'t configured.'));
		}
	}

	/**
	 * Use this before validate or save your datas. Add the ReCaptcha response to the model datas.
	 */
	public function load(){
		$this->controller->request->data[$this->controller->{$this->controller->modelClass}->alias]['g-recaptcha-response']=$this->controller->request->data['g-recaptcha-response'];
	}

}
