<?php

/**
 * Helpers for generate ReCaptcha widget
 */
class RecaptchaHelper extends AppHelper{

	// Load core Form helper
	public $helpers = array('Form');
	// Default settings for the helper
	private $_settings = array(
		'site_key'=>'',
		'theme'=>'light',
		'type'=>'image'
		);

	/**
	 * RecaptchaHelper constructor. Used to merge default settings and user's settings.
	 */
	public function __construct(View $view, $settings=array()){
		parent::__construct($view,$settings);
		$this->_settings = array_merge($this->_settings,$settings);
	}

	/**
	 * Generate code to display ReCaptcha widget. If an error occured, generate the widget with these errors.
	 */
	public function captcha(){
		$captcha = '<div class="g-recaptcha" data-sitekey="' . $this->_settings['site_key'] . '" data-theme="' . $this->_settings['theme'] . '" data-type="' . $this->_settings['type'] . '"></div>';
		if ($this->Form->isFieldError('g-recaptcha-response')) {
    		$captcha = '<div class="error">'.$captcha.$this->Form->error('g-recaptcha-response').'</div>';
		}
		return $captcha;
	}

}
