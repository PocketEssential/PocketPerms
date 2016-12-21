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

namespace PocketEssential\PocketPerms\Events;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerChatEvent;

class Events implements Listener
{

    private $plugin;

    public function __construct(PocketPerms $plugin)
    {

        $this->plugin = $plugin;
    }

     /*
      *  Gets the player GROUP on join (or) set him on the default group
      */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $default = $this->plugin->config->get("default-group");

        if ($this->plugin->getGroup($player) == false) {
            $this->plugin->setGroup($player, $default);
        } else {
            $group = $this->plugin->getGroup($player);
            $this->setGroup($player, $group);
        }
    }
   public function onChat(PlayerChatEvent $event){

      $format = $this->plugin->getChatFormat($event->getPlayer(), $event->getMessage());
        $event->setFormat($format);
    }
}

