<?php
/**
 */

App::uses('String', 'Utility');

/**
 * Dot Shell Class
 *
 * Simple dot generation of PNG files from state-machine
 */
class DotShell extends Shell {

	public $Model;

	public function main() {
		$this->out('Hello world.');
	}

/**
 * This function generates a png file from a given dot. A dot can be generated wit *toDot functions
 * @param  Model	$model		 The model being acted on
 * @param  string   $dot         The contents for graphviz
 * @param  string   $destFile    Name with full path to where file is to be created
 * @return return                returns whatever shell_exec returns
 */
	protected function _generatePng($dot, $destFile) {
		if (!isset($dot)) {
			return false;
		}
		$dotExec = "echo '%s' | dot -Tpng -o%s";
		$path = pathinfo($destFile);
		$command = sprintf($dotExec, $dot, $destFile);
		return shell_exec($command);
	}

	public function generate() {
		$this->out('Generate files');
		switch ($this->args[1]) {
			case 'all':
				$this->out('Load Model:' . $this->args[0]);
				$this->loadModel($this->args[0]);
				$this->Model = new $this->args[0](1);

				// generate all roles
				$dot = $this->Model->createDotFileForRoles($this->Model->roles, array(
					'color' => 'lightgrey',
					'activeColor' => 'green'
					));
				$this->_generatePng($dot, TMP . $this->args[2]);

				foreach ($this->Model->roles as $role => $options) {
					$dot = $this->Model->createDotFileForRoles(array($role => $this->Model->roles[$role]), array(
						'color' => 'lightgrey',
						'activeColor' => 'green'
						));
					$this->_generatePng($dot, TMP . $role . '_' . $this->args[2]);
				}
				# code...
				break;

			default:
				# code...
				break;
		}
	}

}

