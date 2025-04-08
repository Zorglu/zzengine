<?php declare (strict_types=1);

namespace zzengine\Lib;

use zzengine\App\Engine;

/**
 * Classe de base de tous les objets accedant à la base de données
 */
class BaseClass {

    protected Engine $engine;
	protected \StdClass $datas;
	protected bool $modified;

    public function __construct () {
		$this->engine = Engine::create();
    	$this->init();
	}

    protected function init():void {
        $this->datas = new \StdClass();
		$this->modified = false;
    }

	/**
	 * Crud
	 */
	public function create():void {
		$this->modified = false;
	}

	/**
	 * cRud
	 */
	public function read():void {
		$this->modified = false;
	}

	/**
	 * crUd
	 */
	public function update():void{
		$this->modified = false;
	}

	/**
	 * cruD
	 */
	public function delete():void {
		$this->modified = false;
	}

	public function isModified():bool {
		return $this->modified;
	}

	public function getDatas():\StdClass {
		return $this->datas;
	}

    public function __set (string $name, $val):void {
		$this->modified = $val != $this->datas->$name;
		$this->datas->$name = $val;
	}

	public function __get (string $name):mixed {
		if (isset($this->datas->$name)) {
			return $this->datas->$name;
		} else {
			return null;
		}
	}

	public function __isset (string $name):bool {
		return isset($this->datas->$name);
	}

	public function __unset(string $name):void {
		if (isset($this->datas->$name)) {
			unset($this->datas->$name);
		}
	}
}