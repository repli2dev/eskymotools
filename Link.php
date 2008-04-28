/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s HTML tagem <a></a>.
*/
class Link extends Tag {

	/**
	* Konstruktor.
	* @param Object
	* @return void
	*/
	public function __construct($value = NULL) {
		parent :: __construct($value);
		$this->setTag("a");
		$this->setPair();
	}

	/**
	* Nastavi adresu odkazu.
	* @param string Adresa
	* @return void
	*/
	public function href($url) {
		$this->addAtribut("href",$url);
	}
	
	/**
	* Nastavi titulek odkazu.
	* @param string Titulek.
	* @return void
	*/
	public function title($title) {
		$this->addAtribut("title",$title);
	}
}