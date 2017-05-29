<?php
echo shell_exec("clear");
echo "Wellcome to the Action Maker".PHP_EOL;
$menu1 = new menu();
$menu1->setHeader("../Main Menu");
$menu1->addItem("Go user");
$menu1->addItem("Go reports");
$menu1->addItem("Go products");
$menu1->addItem("Exit");

$action1 = new action();
$action1->addAction("echo shell_exec('php -f user.php');");
$action1->addAction("ls change_user.txt");
$action1->addAction("ls reset_user.txt");
$action1->addAction("ls exit.txt");

$menu1->printMenuShell();
recursive($menu1, $action1);

//### Class
class action{
	var $actions = array();
	function action(){
		$this->actions[] = "";
	}
	function addAction($act){
		$this->actions[] = $act;
	}
	function doit($act){
		echo "It has been selected the " . $this->actions[$act] . " Option." . PHP_EOL;
		eval($this->actions[$act]);
	}
}
class menu{
	var $header;
	var $itens = array();
	var $status;
	var $message;
	var $selected;
	function menu(){
		$this->itens[] = "";
		$this->status = true;
		$this->message = "";
   	}
	function printOptionsShell(){
		for ($i=1; $i<count($this->itens); $i++){
			echo " ".$i.") ".$this->itens[$i]. PHP_EOL;
		}
	}
	function addItem($item){
		$this->itens[] = $item;
	}
	function setHeader($header){
		$this->header = $header;
	}
	function printHeaderShell(){
		echo $this->header;
	}
	function printSelector(){
		echo "Select one option: ";
		$this->selected = trim(read_stdin());
	}
	function printMenuShell(){
		echo PHP_EOL . PHP_EOL;
		$this->printHeaderShell();
		echo PHP_EOL . PHP_EOL;
		$this->printOptionsShell();
		echo $this->getMessage().PHP_EOL;
		$this->printSelector();
	}
	function getSelectedOption(){
		return $this->selected;
	}
	function getMessage(){
		return $this->message;
	}
	function isValidSelectedOption(){
		if (array_key_exists($this->selected,$this->itens)){
			$this->message="";
			return true;
		}
		else
		{
			$this->message="This Option is not valid!";
			return false;
		}
	}
}

//### functions
function read_stdin()
{
        $fr=fopen("php://stdin","r");   // open our file pointer to read from stdin
        $input = fgets($fr,128);        // read a maximum of 128 characters
        $input = rtrim($input);         // trim any trailing spaces.
        fclose ($fr);                   // close the file handle
        return $input;                  // return the text entered
}
function recursive($menu1, $action1){
	if($menu1->isValidSelectedOption()){
		echo shell_exec("clear");
		echo "Wellcome to the Action Maker".PHP_EOL;
		echo PHP_EOL . PHP_EOL;
                $menu1->printHeaderShell();
                echo PHP_EOL . PHP_EOL;
                $menu1->printOptionsShell();
		echo PHP_EOL;
		$action1->doit($menu1->getSelectedOption());
	}
	else
	{
		echo shell_exec("clear");
		echo "Wellcome to the Action Maker".PHP_EOL;
		$menu1->printMenuShell();
		recursive($menu1, $action1);
	}
}
?>
