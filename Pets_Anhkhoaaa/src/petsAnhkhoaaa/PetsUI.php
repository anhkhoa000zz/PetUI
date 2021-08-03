<?
namespace petsAnhkhoaaa;

use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\Player;
use petsAnhkhoaaa\Main;
use onebone\pointapi\PointAPI;
use BlockHorizons\BlockPets\Loader as Load;

class PetsUI extends Command{

	private $listPet = [0 => "Pet Gà - 100 Point", 1 => "Pet Bò - 150 Point", 2 => "Pet Dơi - 200 Point", 3 => "Pet Heo - 200 Point", 4 => "Pet Cừu - 200 Point", 5 => "Pet Thỏ - 200 Point", 6 => "Pet Blaze - 350 Point", 7 => "Pet EnderMan - 380 Point"];
	public $plugin;
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
		parent::__construct("petui", "Mở giao diện của Pet");
	}
	
	
	public function execute(CommandSender $p, string $str, array $args): bool {
		if(!$p instanceof Player) return false;
		$this->formPet($p);
		return true;
	}

	public function formPet($p){
		$point = PointAPI::getInstance()->myPoint($p);
		$form = $this->plugin->api->createSimpleForm(function(Player $p, $data){
			if(is_null($data)) return true;
			switch($data){
				case 0:
				$this->buyPet($p);
				break;
				case 1:
				$this->inforPet($p);
				break;
				case 2:
				break;
			}

		});
		$form->setTitle("MENU PET");
		$form->setContent("Số Point hiện tại của bạn: ".$point." Point.\nBạn có thể chọn các chức năng sau đây:\n");
		$form->addButton("MUA PET");
		$form->addButton("CÀI ĐẶT PET");
		$form->addButton("THOÁT");
		$form->sendToPlayer($p);
		return $form;

	}

	public function buyPet($p){
		$point = PointAPI::getInstance()->myPoint($p);
		$form = $this->plugin->api->createCustomForm(function(Player $p, $data){
			if(is_null($data)) return true;
			if(is_null($data[1])){
				$p->sendMessage("Lỗi! Tên không được bỏ trống");
				return true;
			}
			$this->xacNhan($p, $data[0], $data[1]);

		});
		$form->setTitle("MENU PET");
		$form->addDropDown("Số Point hiện tại của bạn: ".$point." Point.\nCó các loại pet sau đây", array_values($this->listPet));
		$form->addInput("Đặt tên cho Pet (Không được bỏ trống)");
		$form->sendToPlayer($p);
		return $form;

	}

	public function xacNhan($p, int $int, string $namePet){
		$pet = $this->getNameandPrice($int);
		$point = PointAPI::getInstance()->myPoint($p);
		$form = $this->plugin->api->createSimpleForm(function(Player $p, $data) use ($pet, $point, $namePet){
			if(is_null($data)) return true;
			switch($data){
				case 0:
				if($point >= $pet[1]){
					$p->sendMessage("MUA PET THÀNH CÔNG!!");
					$command = "spawnpet ".$pet[0]." ".$namePet." 0.5"." true"." ".$p->getName();
					$this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender, $command); 
					PointAPI::getInstance()->reducePoint($p, $pet[1]);
				} else {
					$p->sendMessage("MUA PET THẤT BẠI!");
				}
				break;
				case 1:
				$this->formPet($p);
				break;
			}

		});
		
		$form->setTitle("MENU PET");
		$form->setContent("Số Point hiện tại của bạn: ".$point." Point.\nBạn có chắc chắn mua pet ".$pet[0]." với giá ".$pet[1]." không?");
		$form->addButton("CÓ, TÔI CHẮC CHẮN");
		$form->addButton("QUAY LẠI");
		$form->sendToPlayer($p);
		return $form;

	}

	public function getNameandPrice(int $int){
		switch($int){
			case 0:
			return ["Chicken", 100];
			break;
			case 1:
			return ["Cow", 150];
			break;
			case 2:
			return ["Bat", 200];
			break;
			case 3:
			return ["Pig", 200];
			break;
			case 4:
			return ["Sheep", 200];
			break;
			case 5:
			return ["Rabbit", 200];
			break;
			case 6:
			return ["Blaze", 350];
			break;
			case 7:
			return ["Enderman", 380];
			break;
		}
	}
}