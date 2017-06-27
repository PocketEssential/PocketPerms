<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 6/26/2017
 * Time: 7:52 PM
 */

namespace PocketEssential\PocketPerms\PPListener;


use PocketEssential\PocketPerms\PocketPerms;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;

class JoinListener implements Listener {

	public $plugin;
	public $cache;

	public function __construct(PocketPerms $plugin){

		$this->plugin = $plugin;
	}

	/*
 * Listens on Join
 */

	public function onJoin(PlayerPreLoginEvent $event){

		$player = $event->getPlayer();

		if($this->plugin->getPlayerGroup($player) == null){
			$this->plugin->setGroup($player, $this->plugin->group->get("default-group"));
			$this->plugin->getLogger()->info($player->getName() . " does not have a valid group set. Setting the default one.");
		}else{
			$player->setNameTag($this->plugin->getNameTagFormat($player));
		}

		if($this->plugin->conf->get("cache") == "true"){
			$this->cache[$player->getName()] = array(
				"Group" => $this->plugin->getPlayerGroup($player),
			);
			$this->plugin->getLogger()->info($player->getName() . " cache data has been saved. Next time he/she logs in it will be used.");
		}

		if($this->plugin->getPlayerGroup($player) != null){
			$permissions = $this->plugin->group->get($this->plugin->getPlayerGroup($player))['Permissions'];

			$d = 0;
			foreach($permissions as $pp){
				
			}
		}
	}
}