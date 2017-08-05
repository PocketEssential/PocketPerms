<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 3/22/2017
 * Time: 12:08 AM
 */

namespace PocketEssential\PocketPerms\PPListener;

use PocketEssential\PocketPerms\PocketPerms;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class ChatListener implements Listener {

	/** @var PocketPerms */
	private $plugin;

	/**
	 * @param PocketPerms $plugin
	 */
	public function __construct(PocketPerms $plugin){
		$this->plugin = $plugin;
	}

	/**
	 * @param PlayerChatEvent $event
	 */
	public function onChat(PlayerChatEvent $event){
		$player = $event->getPlayer();

		$event->setFormat((string) $this->plugin->getChatFormat($player, $event->getMessage()));
	}
}