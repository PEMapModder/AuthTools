<?php

namespace authtools\atprovider;

use authtools\AuthTools;
use pocketmine\IPlayer;
use pocketmine\utils\Config;
use SimpleAuth\provider\YAMLDataProvider;
use SimpleAuth\SimpleAuth;

class YAMLAuthToolsProvider extends YAMLDataProvider implements AuthToolsProvider{
	/** @var AuthTools */
	protected $authTools;
	public function __construct(SimpleAuth $simpleAuth, AuthTools $authTools){
		parent::__construct($simpleAuth);
		$this->authTools = $authTools;
		file_put_contents($this->getSimpleAuth()->getDataFolder() . "players/__AUTHTOOLS-VERSION__", $this->getProviderVersion());
	}
	public function getSimpleAuth(){
		return $this->plugin;
	}
	public function getAuthTools(){
		return $this->authTools;
	}
	public function getProviderVersion(){
		return "1.0.0";
	}
	public function registerPlayer(IPlayer $player, $hash){
		$name = trim(strtolower($player->getName()));
		$dir = $this->getSimpleAuth()->getDataFolder() . "players/" . $name{0} . "/";
		if(!is_dir($dir)){
			mkdir($dir);
		}
		$data = new Config($dir . $name . ".yml", Config::YAML);
		$data->set("registerdate", time());
		$data->set("logindate", time());
		$data->set("lastip", null);
		$data->set("hash", $hash);
		$data->set("ipconfig", $this->getAuthTools()->getDefaultIPConfig());
		$data->set("histip", ",");
		$data->save();
		return $data->getAll();
	}
	public function updatePlayer(IPlayer $player, $ip = null, $loginDate = null){
		$data = $this->getPlayer($player);
		if($data !== null){
			if($ip !== null){
				$data["lastip"] = $ip;
				if(strpos($data["histip"], ",$ip,") === false){
					$data["histip"] .= $ip . ",";
				}
			}
			if($loginDate !== null){
				$data["logindate"] = $loginDate;
			}
			$this->savePlayer($player, $data);
		}
	}
	public function close(){
		file_put_contents($this->getSimpleAuth()->getDataFolder() . "players/__AUTHTOOLS-VERSION__", $this->getProviderVersion());
	}
}
