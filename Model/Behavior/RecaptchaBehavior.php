<?php

/**
 * Behavior for generate validation for the ReCaptcha.
 */
class RecaptchaBehavior extends ModelBehavior{

	// Behavior's settings
	private $_settings = array();

	/**
	 * Use at the setup, to merge defaults settings with the user's settings
	 */
	public function setup(Model $model, $settings = array()) {
	    if(!isset($this->_settings[$model->alias])) {
	        $this->_settings[$model->alias] = array(
	            'secret_key' => ''
	        );
	    }
	    $this->_settings[$model->alias] = array_merge(
	        $this->_settings[$model->alias], (array)$settings);
	}

	/**
	 * CallBack run before validation. If the captcha response is wrong, invalidate the model datas.
	 */
	public function beforeValidate(Model $model, $options=array()){
		$response = $this->checkCode($model->data[$model->alias]['g-recaptcha-response'],$this->_settings[$model->alias]['secret_key']);
		if($response===false){
			$model->invalidate('g-recaptcha-response',__d('recaptcha','The robot test is wrong.'));
		}
		return true;
	}

	/**
	 * Call recaptcha API to verify the captcha response.
	 * 
	 * @param string $code It's the recapcha response used to check with the API
	 * @param string $secret The API secret key
	 * 
	 * @return boolean The result of API call
	 */
	private function checkCode($code,$secret){
		$datas = array(
			'secret'=>$secret,
			'response'=>$code
			);

		$url = 'https://www.google.com/recaptcha/api/siteverify?'.http_build_query($datas);
		$response = file_get_contents($url);

		if(empty($response)){
			return false;
		}

		$response = json_decode($response);
		return $response->success;
	}

}
