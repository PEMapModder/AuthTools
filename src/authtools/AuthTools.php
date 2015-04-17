<?php

namespace authtools;

use authtools\atprovider\YAMLAuthToolsProvider;
use pocketmine\plugin\PluginBase;
use SimpleAuth\SimpleAuth;

class AuthTools extends PluginBase{
	const IPAUTH_NONE = 0;
	const IPAUTH_LAST = 1;
	const IPAUTH_ALL = 2;
	/** @var int */
	private $maxNamesPerIp, $maxNamesPerSubnet, $minPasswordLength, $maxPasswordLength, $maxLogins, $ipConfig;
	/** @var SimpleAuth */
	private $simpleAuth;
	/** @var DirectAuth */
	private $directAuth;
	public function onEnable(){
		mkdir($loc = $this->getDataFolder() . "locale/", 0777, true);
		foreach(new \RegexIterator(new \DirectoryIterator($this->getFile()), '#\\.ini$#i') as $file){
			$targ = $loc . basename($file);
			if(!is_file($targ)){
				copy($file, $targ);
			}
		}
		$this->saveDefaultConfig();
		$this->simpleAuth = $this->getServer()->getPluginManager()->getPlugin("SimpleAuth");
		if(!($this->simpleAuth instanceof SimpleAuth)){
			throw new \RuntimeException("Cannot find a valid copy of SimpleAuth loaded");
		}
		$this->simpleAuth->setDataProvider($this->getNewConfigDataProvider());
		/** @noinspection PhpParamsInspection */
		$directAuth = $this->getConfig()->get("directAuth", [
			"enabled" => true,
		]);
		if($directAuth["enabled"]){
			$this->directAuth = new DirectAuth($this);
		}
	}
	public function getMaxNamesPerIp(){
		if(isset($this->maxNamesPerIp)){
			return $this->maxNamesPerIp;
		}
		return $this->maxNamesPerIp = $this->getConfig()->getNested("registration.maxNamesPerIp", 5);
	}
	public function getMaxNamesPerSubnet(){
		if(isset($this->maxNamesPerSubnet)){
			return $this->maxNamesPerSubnet;
		}
		return $this->maxNamesPerSubnet = $this->getConfig()->getNested("registration.maxNamesPerSubnet", 327680);
	}
	public function getMinPasswordLength(){
		if(isset($this->minPasswordLength)){
			return $this->minPasswordLength;
		}
		return $this->minPasswordLength = $this->getConfig()->getNested("registration.minPasswordLength", 4);
	}
	public function getMaxPasswordLength(){
		if(isset($this->maxPasswordLength)){
			return $this->maxPasswordLength;
		}
		return $this->maxPasswordLength = $this->getConfig()->getNested("registration.maxPasswordLength", 256);
	}
	public function getMaxLogins(){
		if(!isset($this->maxLogins)){
			return $this->maxLogins = $this->getConfig()->getNested("login.maxLogins", 5);
		}
		return $this->maxLogins;
	}
	public function getDefaultIPConfig(){
		if(isset($this->ipConfig)){
			return $this->ipConfig;
		}
		switch($value = $this->getConfig()->getNested("login.defaultIpConfig", "last")){
			case "none":
				return $this->ipConfig = self::IPAUTH_NONE;
			case "last":
				return $this->ipConfig = self::IPAUTH_LAST;
			case "all":
				return $this->ipConfig = self::IPAUTH_ALL;
		}
		$this->getLogger()->warning("Invalid value for config setting login.defaultIpConfig: \"$value\"; assumed \"last\"");
		return $this->ipConfig = self::IPAUTH_LAST;
	}
	/**
	 * @return atprovider\AuthToolsProvider
	 */
	public function getNewConfigDataProvider(){
		switch($name = strtolower($this->getConfig()->getNested("dataProvider.type", "yaml"))){
			case "sqlite3":
			case "sqlite":
			throw new \RuntimeException("TODO");
			case "mysql":
				throw new \RuntimeException("TODO");
			default:
				$this->getLogger()->warning("Unknown data provider type: \"$name\"");
			case "yaml":
				return new YAMLAuthToolsProvider($this->simpleAuth, $this);
		}
	}
	public static function hash($password, $salt){
		return bin2hex(hash("sha512", $password . $salt, true) ^ hash("whirlpool", $salt . $password, true));
	}
}
