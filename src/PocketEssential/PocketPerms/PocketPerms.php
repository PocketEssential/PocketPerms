<?php
#
#  _____           _        _   _____
# |  __ \         | |      | | |  __ \
# | |__) |__   ___| | _____| |_| |__) |__ _ __ _ __ ___  ___
# |  ___/ _ \ / __| |/ / _ \ __|  ___/ _ \ '__| '_ ` _ \/ __|
# | |  | (_) | (__|   <  __/ |_| |  |  __/ |  | | | | | \__ \
# |_|   \___/ \___|_|\_\___|\__|_|   \___|_|  |_| |_| |_|___/
#
# Made by PocketEssential Copyright 2016 ©
#
# This is a public software, you cannot redistribute it a and/or modify any way
# unless otherwise given permission to do so.
#
# Author: The PocketEssential Team
# Link: https://github.com/PocketEssential
#
#|------------------------------------------------- PocketPerms -------------------------------------------------|
#| - If you want to suggest/contribute something, read our contributing guidelines on our Github Repo (Link Below)|
#| - If you find an issue, please report it at https://github.com/PocketEssential/PocketPerms/issues             |
#|----------------------------------------------------------------------------------------------------------------|

namespace PocketEssential\PocketPerms;


use EconomyPlus\EconomyPlus;
use onebone\economyapi\EconomyAPI;
use PocketEssential\PocketPerms\Commands\PP;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class PocketPerms extends PluginBase implements Listener {

	const PERMISSION = ".";
	const RUN_FROM_CONSOLE = "Please run this command from console";
	public $group;
	public $conf;
	public $chat;
	public $data;

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents(new PPListener\ChatListener($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new PPListener\JoinListener($this), $this);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getReady();

		$this->getServer()->getCommandMap()->registerAll('PocketPerms', [
			new PP($this)
		]);

		$this->getLogger()->notice("---------- PocketPerms ---------");
		$this->getLogger()->notice("    Loaded, & ready to use     ");
		$this->getLogger()->notice("-------------------------------");

	}

	public function getReady(){

		if(!is_dir($this->getDataFolder())){
			@mkdir($this->getDataFolder());
		}

		$this->saveResource("groups.yml");
		$this->saveResource("chat.yml");
		$this->saveResource("config.yml");
		$this->saveResource("data.yml");
		$this->group = new Config($this->getDataFolder() . "groups.yml", Config::YAML);
		$this->chat = new Config($this->getDataFolder() . "chat.yml", Config::YAML);
		$this->conf = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->data = new Config($this->getDataFolder() . "data.yml", Config::YAML);
	}

	public function onDisable(){
		$this->group->save();
		$this->conf->save();
		$this->chat->save();
		$this->data->save();
	}

	/*
	 *  Gets a player group
	 */

	public function getChatFormat($player, $message){

		if($this->chat instanceof Config && $player instanceof Player){

			$group = $this->getPlayerGroup($player);
			$format = $this->chat->get($group);
			$format = $format['Chat'];
			$format = str_replace("{player_nametag}", $player->getNameTag(), $format);
			$format = str_replace("{player_name}", $player->getName(), $format);
			$format = str_replace("{color}", "§", $format);
			$format = str_replace("{message}", $message, $format);

			if($this->getServer()->getPluginManager()->getPlugin("FactionsPro") != null){
				$format = str_replace("{Factions_Pro}", $this->getServer()->getPluginManager()->getPlugin("FactionsPro")->getPlayerFaction($player), $format);
			}
			if($this->getServer()->getPluginManager()->getPlugin("EconomyPlus") != null){
				$format = str_replace("{EconomyPlus_Money}", EconomyPlus::getInstance()->getMoney($player), $format);
			}
			if($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") != null){
				$format = str_replace("{EconomyAPI_Money}", EconomyAPI::getInstance()->myMoney($player), $format);
			}

			$format = str_replace("{Online}", count($this->getServer()->getOnlinePlayers()), $format);
			$format = str_replace("{Max}", $this->getServer()->getMaxPlayers(), $format);
			$format = str_replace("{Level}", $player->getLevel()->getName(), $format);
			$format = str_replace("{Health}", $player->getHealth(), $format);
			return $format;
		}else{
			return false;
		}
	}

	/*
	 * Sets a player group
	 */

	public function getPlayerGroup($player){

		if($this->data instanceof Config && $player instanceof Player){
			$group = $this->data->get($player->getName());

			if($group == null){
				return null;
			}else{
				return $group;
			}
		}
	}

	/*
	 * Get a group chat format
	 */

	public function getNameTagFormat($player){

		if($this->chat instanceof Config){
			if($player instanceof Player){
				$group = $this->getPlayerGroup($player);
				$format = $this->chat->get($group);
				$format = str_replace("{player_nametag}", $player->getNameTag(), $format);
				$format = str_replace("{player_name}", $player->getName(), $format);
				$format = str_replace("{color}", "§", $format);

				if($this->getServer()->getPluginManager()->getPlugin("FactionsPro") != null){
					$format = str_replace("{Factions_Pro}", $this->getServer()->getPluginManager()->getPlugin("FactionsPro")->getPlayerFaction($player), $format);
				}
				if($this->getServer()->getPluginManager()->getPlugin("EconomyPlus") != null){
					$format = str_replace("{EconomyPlus_Money}", EconomyPlus::getInstance()->getMoney($player), $format);
				}
				if($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") != null){
					$format = str_replace("{EconomyAPI_Money}", EconomyAPI::getInstance()->myMoney($player), $format);
				}

				$format = str_replace("{Online}", count($this->getServer()->getOnlinePlayers()), $format);
				$format = str_replace("{Max}", $this->getServer()->getMaxPlayers(), $format);
				$format = str_replace("{Level}", $player->getLevel()->getName(), $format);
				$format = str_replace("{Health}", $player->getHealth(), $format);
				return $format;

			}else{

				return false;
			}
		}
	}

	/*
	 * Get a group name tag format
	 */

	public function getGroup($group){

		$gr = $this->group;

		if($gr->get($group) != null){
			return true;
		}else{
			return false;
		}
	}

	/*
	 * Checks if a group  exist
	 */

	public function setGroup($player, $group){

		if($this->data instanceof Config && $player instanceof Player){
			$this->data->set($player->getName(), $group);
			$this->data->save();
		}else{
			return false;
		}
	}

	/*
	 * Add a permission to a group
	 */

	public function addGroupPermission($group, $pp){

		if($this->group instanceof Config){

			$gr = $this->group;
			$pp = $gr->get($group)['Permissions'];
			$pp[] = $pp;

			$gr->set($group['Permissions'], $pp);
			return true;
		}else{
			return false;
		}
	}

	/*
    * Remove a permission from a group
    */
	public function deleteGroupPermission($group, $pp){

		if($this->group instanceof Config){
			$permission = $this->group->get("Groups");
			$permissions = $permission[$group]['Permissions'];

			$result = array_diff($permission, $pp);
			$pp = $this->group->get("Groups");
			$ppp = $pp[$group]['Permissions'];
			$this->group->set($ppp, $result);

			return true;
		}else{
			return false;
		}
	}

	/*
	 * Delete a group from the list of groups
	 */
	public function deleteGroup($group){

		if($this->group instanceof Config){
			$group = $this->group->get("Groups");

			$remove = array($group);
			$result = array_diff($group, $remove);
			$this->group->set("Groups", $result);

			return true;
		}else{
			return false;
		}
	}

	/*
	 *  Returns the list of groups in array
	 */
	public function getGroups(){
		$pie = $this->group->getAll();
		$cupcake = $pie['Groups'];

		var_dump($cupcake);
	}

	/*
	 *  Sets a group chat format
	 */
	public function setGroupFormat($group, $format){

		if($this->chat instanceof Config){
			$this->chat->set($group, $format);
		}else{
			return false;
		}
	}
}

