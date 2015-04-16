<?php

namespace authtools;

use pocketmine\plugin\PluginBase;

class AuthTools extends PluginBase{
	public function onEnable(){
		mkdir($loc = $this->getDataFolder() . "locale/", 0777, true);
		foreach(new \RegexIterator(new \DirectoryIterator($this->getFile()), '#\\.ini$#i') as $file){
			$targ = $loc . basename($file);
			if(!is_file($targ)){
				copy($file, $targ);
			}
		}
		$this->saveDefaultConfig();
	}
}
