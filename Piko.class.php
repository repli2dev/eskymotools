<?php
/**
* @package eskymoFW
* @author Eskymaci
* @license http://www.gnu.org/licenses/gpl.html
*/

/**
* Piko je trida pro praci s ikonami (obrazky). Adresar, se kterym pracuje, musi byt nastaveny na CHMOD 0777.
* @example doc_example/Piko.class.phps
* @package eskymoFW
*/

class Piko {

	/**
	* @var array Pole znaku, ktere maji byt nahrazeny.
	* @example changeChar.php
	*/
	private static $changeChar = array();    
    
	/**
	* @var int CHMOD ulozeneho obrazku.
	*/
	const CHMOD = 0777;

	/**
	 * @var mixed Pole podporovanych koncovek (typu).
	 */
	private static $supported = array("jpg","jpeg","png","gif");
	
	/**
	* @var string Adresar, se kterym se pracuje.
	* @example directory.php
	*/
	public static $directory = "./";
	
	/**
	* @var int Vyska obrazku.
	*/
	private static $imgHeight = 0;

	/**
	* @var int Sirka obrazku.
	*/
	private static $imgWidth = 0;
	
	/**
	 * @var string Typ zpracovavaneho obrazku.
	 */
	private static $imgType = "";
	
	/** 
	* @var int Puvodni vyska obrazku.
	*/
	private static $imgHeightOrg = 0;

	/**
	* @var int Puvodni sirka obrazku.
	*/
	public static $imgWidthOrg = 0;
	
	/**
	* @var int Maximalni vyska obrazku.
	*/
	public static $imgMaxHeight = 100;

	/**
	* @var int Max sirka obrazku
	*/            
	public static $imgMaxWidth = 100;
	
	/**
	* @var int Maximalni datova velikost (v bytech). Pokud se nema kontrolovat, nastavte na 0.
	*/
	public static $imgMaxSize = 0;

	/**
	* @var string Nazev obrazku
	*/
	private static $imgName = "";
	
	/**
	 * Docasna adresa k obrazku.
	 */
	public static $imgTemporaryName = "";
    
	/**
	* @var string Prefix pro ukladane obrazky.
	*/
	public static $imgPrefix = "";

	/**
	* Zmeni specialni znaky v nazvu obrazku definovane v poli specialnich znaku.
	* @see Piko::$changeChar
	* @param string
	* @return string
	*/
	protected static function changeChar($string) {
		if (count(self::$changeChar)) {
			return strTr($string,self::$changeChar);
		}
		else return $string;
	}
	
	/**
	* Vrati pole znaku, ktere maji byt zamenovany.
	* @see Piko::$changeChar
	* @return mixed
	*/
	public static function getChangeChar() {
		return self::$changeChar;
	}

	/**
	 * Vrati adresar, do ktereho se obrazky nahravaji.
	 * @see Piko::$directory
	 * @return string
	 */
	public static function getDirectory() {
		return self::$directory;
	}

	/**
	* Vrati nazev posledniho ulozeneho obrazku.
	* @return string
	*/
	public static function getLast() {
		return self::$imgName;
	}
	
	/**
	 * Vrati maximalni vysku nahravanych obrazku.
	 * @see Piko::$imgMaxHeight
	 * @return int
	 */
	public static function getMaxHeight() {
		return self::$imgMaxHeight;
	}
	
	/**
	 * Vrati maximalni datovou velikost (v bytech) nahravanych obrazku. Pokud je nastavena na 0, neni velikost kontrolovana.
	 * @see Piko::$imgMaxSize
	 * @return int
	 */
	public static function getMaxSize() {
		return self::$imgMaxSize;
	}
	
	/**
	 * Vrati maximalni sirku nahravanych obrazku.
	 * @see Piko::$imgMaxWidth
	 * @return int
	 */
	public static function getMaxWidth() {
		return self::$imgMaxWidth;
	}
	
	/**
	* Vrati cestu k poslednimu nahranemu obrazku.
	* @return string
	*/
	public static function getPathOfLast() {
		return self::$directory.self::$imgName;
	}
	
	/**
	 * Vrati pouzivany prefix pro nahravane obrazky.
	 * @see Piko::$imgPrefix
	 * @return string
	 */
	public static function getPrefix() {
		return self::$imgPrefix;
	}
	
	/**
	* Vrati datovou velikost posledniho nahraneho obrazku.
	* @return int
	*/
	public static function getSizeOfLast() {
		return filesize(self::getPathOfLast());
	}

	/**
	 * Vrati podporovane typy obrazku.
	 * @see Piko::$supported
	 * @return mixed 
	 */
	public static function getSupported() {
		return self::$supported;
	}
	
	/**
	* Zkontroluje typ obrazku a nahraje informace o obrazku. Poku neni typ obrazku podporovan nebo je obrazek datove prilis velky (a tudiz se akce nezdari), vrati FALSE, jinak TRUE.
	* @param FILES_array
	* @return boolean      
	*/
	protected static function load($img) {
		try {
			$path = getImageSize($img["tmp_name"]);
			$help = TRUE;
			foreach (self::getSupported() AS $end) {
				if (strPos(strToLower($img["name"]),$end)) {
					self::$imgType = $end;
					$help = FALSE;
				}
			}
			if (($help) or ((filesize($img["tmp_name"]) > self::$imgMaxSize) and (self::$imgMaxSize != 0))) {
				throw new Error("");
			}
			self::$imgWidth = $path[0];
			self::$imgWidthOrg = $path[0];
			self::$imgHeight = $path[1];
			self::$imgHeightOrg = $path[1];    
			self::$imgName = $img["name"];
			self::$imgTemporaryName = $img["tmp_name"];
			return TRUE;
		}
		catch(Error $e) {
			return FALSE;
		}
	} 	

	/**
	* Zkontroluje rozmery obrazku a pripadne je i zmeni.
	* @return void
	*/
	private static function reSize() {
		if (self::$imgWidthOrg > self::$imgMaxWidth) {
			self::$imgHeight = (self::$imgMaxWidth/self::$imgWidthOrg)*self::$imgHeightOrg;
			self::$imgWidth = self::$imgMaxWidth;
		}
		if (self::$imgHeightOrg > self::$imgMaxHeight) {
			self::$imgWidth = (self::$imgMaxHeight/self::$imgHeightOrg)*self::$imgWidthOrg;
			self::$imgHeight = self::$imgMaxHeight;   
		}   
	}
	
	/**
	* Ulozi obrazek.
	* @param string Nazev ukladaneho obrazku. Pokud je meteda volana bez tohoto parametru, bude obrazek ulozen pod puvodnim nazvem.
	* @return string Nazev ulozeneho obrazku.
	*/ 
	private static function save($imgName = NULL) {
		if (!$imgName) {
			$imgName = self::$imgName;
		}
		else {
			$imgName .= ".".self::$imgType;
		}
		$imgName = self::changeChar($imgName);            
		$out = imagecreatetruecolor(self::$imgWidth,self::$imgHeight);
		switch(self::$imgType) {
			case "jpg":
			case "jpeg":
				$source = ImageCreateFromJpeg(self::$imgTemporaryName);
				ImageCopyResized ($out,$source,0,0,0,0,self::$imgWidth,self::$imgHeight,self::$imgWidthOrg,self::$imgHeightOrg);
				ImageJpeg($out, self::$directory.self::$imgPrefix.$imgName, 50);
				break;
			case "png":
//TODO: Tato metoda nefunguje pro PNG, melo by se to opravit.
				$source = ImageCreateFromPng(self::$imgTemporaryName);
				ImageCopyResized ($out,$source,0,0,0,0,self::$imgWidth,self::$imgHeight,self::$imgWidthOrg,self::$imgHeightOrg);
				ImagePng($source, self::$directory.self::$imgPrefix.$imgName, 50);
				break;
			case "gif":
				$source = ImageCreateFromGif(self::$imgTemporaryName);
				ImageCopyResized ($out,$source,0,0,0,0,self::$imgWidth,self::$imgHeight,self::$imgWidthOrg,self::$imgHeightOrg);
				ImageGif($source, self::$directory.self::$imgPrefix.$imgName, 50);				
				break;
		}
		chmod (self::$directory.self::$imgPrefix.$imgName,self::CHMOD);
		ImageDestroy($out);
		ImageDestroy($source);
		self::$imgName = $imgName;
		return $imgName;             
	} 
	
	/**
	* Nastavi pole znaku, ktere maji byt zamenovany.
	* @see Piko::$changeChar
	* @param mixed
	* @return void
	*/
	public static function setChangeChar($chars) {
		self::$changeChar = $chars;
	}

	/**
	 * Nastavi adresar, do ktereho se obrazky nahravaji.
	 * @see Piko::$directory
	 * @param string
	 * @return void
	 */
	public static function setDirectory($dir) {
		self::$directory = $dir;
	}
	
	/**
	 * Nastavi maximalni vysku nahravanych obrazku.
	 * @see Piko::$imgMaxHeight
	 * @param int
	 * @return void
	 */
	public static function setMaxHeight($height) {
		self::$imgMaxHeight = $height;
	}
	
	/**
	 * Nastavi maximalni datovou velikost (v bytech) nahravanych obrazku. Pokud je nastavena na 0, neni velikost kontrolovana.
	 * @see Piko::$imgMaxSize
	 * @param int
	 * @return void
	 */
	public static function setMaxSize($size) {
		self::$imgMaxSize = $size;
	}
	
	/**
	 * Nastavi maximalni sirku nahravanych obrazku.
	 * @see Piko::$imgMaxWidth
	 * @param int
	 * @return void
	 */
	public static function setMaxWidth($width) {
		self::$imgMaxWidth = $width;
	}
	
	/**
	 * Vrati pouzivany prefix pro nahravane obrazky.
	 * @see Piko::$imgPrefix
	 * @param string
	 * @return void
	 */
	public static function setPrefix($prefix) {
		self::$imgPrefix = $prefix;
	}
 
	/**
	* Zpracuje obrazek => nahraje ho na pozadovane misto.
	* @param FILES_array
	* @param string Nazev obrazku bez koncovky. Pokud neni vyplneno, bude mit ulozeny obrazek nazev nahravaneho obrazku.
	* @return boolean
	* @example work.php   
	*/
	public static function work($img,$name = NULL) {
		try { 
			if (self::load($img) != TRUE) {
				throw new Exception;
			}
			self::resize();
			self::save($name);             
			return TRUE;
		}
		catch(Exception $e) {
			return FALSE;          
		}
	}
}
?>

