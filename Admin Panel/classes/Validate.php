<?php
class Validate{
	private $_passed = false,
			$_errors =  array(),
			$_db = null;

	public function __construct(){

			$this->_db = DB::getInstance();

	}

	public function check($source, $items = array()){
		
		foreach($items as $item => $rules ){
			
			foreach ($rules as $rule => $rule_value) {
			
				$value = trim($source[$item]);
				//$value = preg_replace('/\s+/', '', $value);
				$sanitizer = new Sanitize();
				$item = $sanitizer->clean($item);
				
				
					switch ($rule) {
						case 'required':
							if (empty($value)) {
									$this->addError(array(
										'field' => $item,
										'message' => "{$item} is required",
										));
							} 
							break;
						case 'min':
							if (strlen($value) < $rule_value) {
							 	$this->addError(array(
							 		'field' => $item,
							 		'message' =>	"{$item} must have at least {$rule_value} character(s)",
							 		));
							}
							break;

						case 'max':
							if (strlen($value) > $rule_value) {
							 	$this->addError( array(
							 		'field' => $item,
							 		'message' => "{$item} must be a maximum of {$rule_value} characters",
							 		));
							}
							break;

						case 'matches':
							if ( $value != $source[$rule_value] ) {
								$this->addError( array(
							 		'field' => $item,
							 		'message' => "{$rule_value} must match {$item}",
							 		));
							}
							break;

						case 'alphanumeric':
							if ( is_numeric($value) ) {

								$this->addError(array(
							 		'field' => $item,
							 		'message' => "{$item} must contain at least 1 word",
							 		));
							}
							break;

						case 'unique':
							$check = $this->_db->where($rule_value['field'],$value)->getOne($rule_value['table'],$rule_value['field']);
							
							
							if (count($check) > 0) {
								$this->addError(array(
							 		'field' => $item,
							 		'message' => "The {$item} {$check[$rule_value['field']]} has been already taken",
							 		));
							}
							break;

						case 'exists':
							$check = $this->_db->where($rule_value['field'],$value)->getOne($rule_value['table'],$rule_value['field']);
							
							
							if (count($check) == 0) {
								$this->addError(array(
							 		'field' => $item,
							 		'message' => "sorry, we couldn't find this {$item} {$check[$rule_value['field']]}",
							 		));
							}
							break;

						case 'email':
								$pattern = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';	

							if (preg_match($pattern, $value) !== 1 ) {

										if ($value == '') {
											# code...
										} else {
											$this->addError(array(
									 		'field' => $item,
									 		'message' => "Invalid email format",
									 		));
										}
							   			
							}

						break;

						
						default:
							# code...
							break;
					} 
			}
		
		}

		if(empty($this->_errors)){
			$this->_passed = true;
		}

		return $this;
	}

	private function addError($error){
		$this->_errors[] = $error;
	}

	public function errors(){
		return $this->_errors;
	}

	public function passed(){
		return $this->_passed; 
	}
}