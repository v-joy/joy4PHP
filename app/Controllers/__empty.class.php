<?php
class emptyController extends Controller{
	public function __empty() {
				$module = Dispatcher::getModule();
		$action = Dispatcher::getAction();
		ob_start();
		D($this->getGets());
		$get = ob_get_contents();
		ob_clean();
		
echo <<<EOT
debuging:
	module:$module;
	Action:$action;
	Get:$get;
EOT;
	}
}