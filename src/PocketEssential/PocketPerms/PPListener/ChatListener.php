<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 3/22/2017
 * Time: 12:08 AM
 */

namespace PocketEssential\PocketPerms\PPListener;

use PocketEssential\PocketPerms\PocketPerms;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerChatEvent;

class ChatListener implements Listener
{

    public $plugin;
    public $cache;

    public function __construct(PocketPerms $plugin)
    {

        $this->plugin = $plugin;
    }


    /*
     * Listens on Join
     */

    public function onJoin(PlayerJoinEvent $event)
    {

        $player = $event->getPlayer();

        if ($this->plugin->getPlayerGroup($player) == null) {
            $this->plugin->setGroup($player, $this->plugin->groups->get("default-group"));
        } else {
            $player->setNameTag($this->plugin->getNameTagFormat($player));
        }

        if($this->plugin->main->get("cache") == "true"){
            $this->cache[$player->getName()] = array(
                "Group" => $this->plugin->getPlayerGroup($player),
            );
        }

        if ($this->plugin->getPlayerGroup($player) != null) {
            $permission = $this->plugin->groups->get("Groups");
            $permissions = $permission[$this->plugin->getPlayerGroup($player)]['Permissions'];

            foreach ($permissions as $pp) {
                $this->plugin->addPermission($player, $pp);
                $this->plugin->getServer()->getLogger()->critical("$pp");
            }
        }
    }

    /*
     *  Listens on Chat
     */

    public function onChat(PlayerChatEvent $event){

        $player = $event->getPlayer();

        $event->setFormat($this->plugin->getChatFormat($player, $event->getMessage()));
    }
}