<?php
namespace mcpe;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use jojoe77777\FormAPI;

class test extends PluginBase implements Listener{
	
	private $food = "";

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    	$this->getLogger()->info("§aทำงาน");
        @mkdir ( $this->getDataFolder () );
		$this->saveResource("config.yml");
		$this->saveDefaultConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool
    {
        if ($command->getName() == "test") {
            $this->form($sender);
            return true;
		}
	}

    public function form(Player $sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, $data = null) {
            $animal_name = $data;
            if ($data !== null) {
                $this->Animal($player,$animal_name);
			}
		});
        $form->setTitle("test");
        $con = $this->getConfig();
        $config = $con->getAll();
        $animals = array_keys($config["Animals"]);
        foreach($animals as $animal){
            $form->addButton($animal, -1, "", $animal);
        }
		$form->sendToPlayer($sender);
		return $form;
	}
	public function Animal($player, $animal_name)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $player, $data) {
            if ($data === true) {
                return true;
            }
        });
        $con = $this->getConfig();
        $config = $con->getAll();
        $form->setTitle("Animal " . $animal_name);
        $form->setContent("อาหาร: " . $config["Animals"][$animal_name]["food"]);
        $form->setButton1("§aตกลง");
        $form->setButton2("§cยกเลิก");
        $player->sendForm($form);
	}
}

