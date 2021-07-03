<?php

class Sanitize{

	public function clean($Input){
		return htmlentities($Input, ENT_QUOTES, 'UTF-8');
	}

}