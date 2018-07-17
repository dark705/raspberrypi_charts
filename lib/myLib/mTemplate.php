<?php
class mTemplate {
	private $html;

	public function __construct($file, $var = array()){
		foreach($var as $key => $item){
			$$key = $item;
		}
		
		ob_start();
			include $file;
		$this->html = ob_get_clean();
	}
	
	public function get(){
		return $this->html;
	}
	
	public function show(){
		echo $this->html;
	}
}
?>