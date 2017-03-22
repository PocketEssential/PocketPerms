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


use PocketEssential\PocketPerms\Commands\PP;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class PocketPerms extends PluginBase implements Listener
{

    public $groups;
    public $chatFormat;
    public $data;
    public $main;
    const PERMISSION = ".";
    const RUN_FROM_CONSOLE = "Please run this command from console";

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new PPListener\ChatListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->main = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->chatFormat = new Config($this->getDataFolder() . "chat.yml", Config::YAML);
        $this->groups = new Config($this->getDataFolder() . "groups.yml", Config::YAML);
        $this->data = new Config($this->getDataFolder() . "data.yml", Config::YAML);
        $this->getServer()->getCommandMap()->registerAll('NetworkSystem', [
            new PP($this)
        ]);

    }

    public function onDisable()
    {
        $this->main->save();
        $this->chatFormat->save();
        $this->groups->save();
        $this->data->save();
    }


    public function getPlayerGroup($player)
    {

        if ($this->data instanceof Config && $player instanceof Player) {
            $group = $this->data->get($player->getName());

            if ($group == null) {
                return null;
            } else {
                return $group;
            }
        }
    }

    public function setGroup($player, $group)
    {

        if ($this->data instanceof Config && $player instanceof Player) {
            $this->data->set($player->getName(), $group);
            $this->data->save();
        } else {
            return false;
        }
    }

    public function getChatFormat($player, $message)
    {

        if ($this->chatFormat instanceof Config && $player instanceof Player) {

            $group = $this->getPlayerGroup($player);
            $format = $this->chatFormat->get($group);
            $format = $format['Chat'];
            $format = str_replace("{player_nametag}", $player->getNameTag(), $format);
            $format = str_replace("{player_name}", $player->getName(), $format);
            $format = str_replace("{message}", $message, $format);
            $format = str_replace("{color}", "§", $format);
            return $format;
        } else {
            return false;
        }
    }

    public function getNameTagFormat($player)
    {

        if ($this->chatFormat instanceof Config) {
            if ($player instanceof Player) {
                $group = $this->getPlayerGroup($player);
                $format = $this->chatFormat->get($group);
                $format = $format['Nametag'];
                $format = str_replace("{player_nametag}", $player->getNameTag(), $format);
                $format = str_replace("{player_name}", $player->getName(), $format);
                $format = str_replace("{color}", "§", $format);
                return $format;

            } else {

                return false;
            }
        }
    }

    public function getGroup($group)
    {

        $g = $this->groups->get("Groups");

        if ($g[$group] != null) {
            return true;
        } else {
            return false;
        }
    }

    public function addPermission($player, $pp, $permissions = null)
    {

        if ($player instanceof Player) {
            $player->addAttachment($this, $pp, true);
        } else {
            return false;
        }
    }

    public function addGroupPermission($group, $pp)
    {

        if ($this->groups instanceof Config) {
            $permission = $this->groups->get("Groups");
            $permissions = $permission[$group]['Permissions'];
            $updated = $permissions;
            $updated[] = "$pp";
            $da = $this->groups->get("Groups");
            $da = $da[$group];

            $v = $this->groups->get("Groups");

        } else {
            return false;
        }
    }
}