<?php

namespace Gaprix\FixPlayerDuplication;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\UUID;

class FixPlayerDuplication extends PluginBase implements Listener
{

	public $onlinePlayers = [];

	public function onEnable()
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onDataPacketReceive(DataPacketReceiveEvent $event)
	{
		$packet = $event->getPacket();
		$player = $event->getPlayer();
		if($packet instanceof LoginPacket) {
			foreach($this->onlinePlayers as $existingPlayer) {
				if(strcasecmp($existingPlayer->getName(), $packet->username) === 0 || ($existingPlayer->getUniqueId() != null && $existingPlayer->getUniqueId()->equals(UUID::fromString($packet->clientUUID)))) {
					$ev = new PlayerDuplicateLoginEvent($player, $existingPlayer);
					$this->getServer()->getPluginManager()->callEvent($ev);
					if($ev->isCancelled()) {
						$player->kick($ev->getDisconnectMessage());
						return;
					}
					$existingPlayer->kick($ev->getDisconnectMessage());
					unset($this->onlinePlayers[$existingPlayer->getName()]);
				}
			}
			$this->onlinePlayers[$packet->username] = $player;
		}
	}

	public function onKick(PlayerKickEvent $event)
	{
		unset($this->onlinePlayers[$event->getPlayer()->getName()]);
	}

	public function onDisconnect(PlayerQuitEvent $event)
	{
		unset($this->onlinePlayers[$event->getPlayer()->getName()]);
	}

}