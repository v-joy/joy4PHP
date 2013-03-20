<?php
class emptyController extends Controller{
	public function __empty() {
		echo "oops,404....";
		$module = Dispatcher::getModule();
		$action = Dispatcher::getAction();
		ob_start();
		D($_GET);
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