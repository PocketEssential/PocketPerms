<?php
declare(strict_types=1);
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
use PocketEssential\PocketPerms\Permission\RemovePermission;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class PocketPerms extends PluginBase implements Listener {

	const PERMISSION = ".";
	const RUN_FROM_CONSOLE = "Please run this command from console";
	/** @var Config */
	public $group;
	/** @var Config */
	public $conf;
	/** @var Config */
	public $chat;
	/** @var Config */
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

	/**
	 * Gets the chat format
	 *
	 * @param $player
	 * @param $message
	 *
	 * @return bool|mixed
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

	/**
	 * Gets a player's group
	 *
	 * @param Player $player
	 *
	 * @return bool|mixed|null
	 */
	public function getPlayerGroup(Player $player){
		$group = $this->data->get($player->getName());

		if($group == null){
			return null;
		}else{
			return $group;
		}
	}

	/**
	 * Gets the nametag format
	 *
	 * @param Player $player
	 *
	 * @return bool|mixed
	 */
	public function getNameTagFormat(Player $player){
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
	}

	/**
	 * Checks if a group exists
	 *
	 * @param $group
	 *
	 * @return bool
	 */
	public function getGroup($group){

		$gr = $this->group;

		if($gr->get($group) != null){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Sets a player's group
	 *
	 * @param Player $player
	 * @param        $group
	 *
	 * @return bool
	 */
	public function setGroup(Player $player, $group){
		$this->data->set($player->getName(), $group);
		$this->data->save();

		return true;
	}

	/**
	 * Adds a permission to a group
	 *
	 * @param $group
	 *
	 * @return bool
	 * @internal param $pp
	 *
	 */
	public function addGroupPermission($group){

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

	/**
	 * Removes a permission from a group
	 *
	 * @param $group
	 * @param $pp
	 */
	public function deleteGroupPermission($group, $pp){
		$rm = new RemovePermission($this, $group, $pp);
		$rm->int();
	}

	/**
	 * Deletes a group from the config
	 *
	 * @return bool
	 */
	public function deleteGroup(){
		$group = $this->group->get("Groups");

		$remove = [$group];
		$result = array_diff($group, $remove);
		$this->group->set("Groups", $result);

		return true;
	}

	/**
	 * Returns the list of groups in array
	 */
	public function getGroups(){
		//TODO: Actually make this
	}

	/**
	 * Sets a group format
	 *
	 * @param $group
	 * @param $format
	 */
	public function setGroupFormat($group, $format){
		$this->chat->set($group, $format);
	}
}

