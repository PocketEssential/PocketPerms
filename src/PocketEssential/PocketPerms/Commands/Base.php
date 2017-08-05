<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 3/22/2017
 * Time: 12:54 AM
 */

namespace PocketEssential\PocketPerms\Commands;

use PocketEssential\PocketPerms\PocketPerms;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

class Base extends Command implements PluginIdentifiableCommand {

	/** @var PocketPerms */
	private $plugin;

	/**
	 * @param PocketPerms    $plugin
	 * @param string         $name
	 * @param string         $description
	 * @param string         $usageMessage
	 * @param                $aliases
	 */
	public function __construct(PocketPerms $plugin, string $name, string $description, string $usageMessage, array $aliases){
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->plugin = $plugin;
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if($sender->hasPermission($this->getPermission())){
			$result = $this->onExecute($sender, $args);
			if(is_string($result)){
				$sender->sendMessage($result);
			}

			return true;
		}else{
			$sender->sendMessage(PocketPerms::PERMISSION);
		}

		return false;
	}

	/**
	 * @param CommandSender $sender
	 * @param array         $args
	 */
	public function onExecute(CommandSender $sender, array $args){

	}

	/**
	 * @return Plugin|PocketPerms
	 */
	public function getPlugin() : Plugin{
		return $this->plugin;
	}
}