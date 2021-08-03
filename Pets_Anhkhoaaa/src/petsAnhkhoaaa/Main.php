<?
namespace petsAnhkhoaaa;

use pocketmine\{Server,Player};
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use petsAnhkhoaaa\PetsUI;

class Main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getCommandMap()->register("petui", new PetsUI($this));
		$this->api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$this->pet = $this->getServer()->getPluginManager()->getPlugin("BlockPets");
	}
	
}