<?php
/**
* @package eskymoFW
* @author Eskymaci
* @license http://www.gnu.org/licenses/gpl.html
*/

/**
* Piko je trida pro praci s ikonami (obrazky). Adresar, se kterym pracuje, musi byt nastaveny na CHMOD 0777.
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
	* @var string Adresar, se kterym se pracuje.
	* @example directory.php
	*/
	private static $directory = "";

	/**
	* @var int Vyska obrazku.
	*/
	private static $imgHeight = 0;

	/** 
	* @var int Puvodni vyska obrazku.
	*/
	private static $imgHeightOrg = 0;

	/**
	* @var int Maximalni vyska obrazku.
	*/
	private static $imgMaxHeight = 100;

	/**
	* @var int Maximalni datova velikost (v bytech). Pokud se nema kontrolovat, nastavte na 0.
	*/
	const IMG_MAX_SIZE = 0;

	/**
	* @var int Max sirka obrazku
	*/            
	const IMG_MAX_WIDTH = 60;
    
	/**
	* @var string Nazev obrazku
	*/
	private static $imgName = "";
    
	/**
	* @var string Prefix pro ukladane obrazky.
	*/
	const IMG_PREFIX = "";

	/**
	* @var int Sirka obrazku.
	*/
	private static $imgWidth = 0;

	/**
	* @var int Puvodni sirka obrazku.
	*/
	private static $imgWidthOrg = 0;

	/**
	* @var string Chybova hlaska pro spatny typ obrazku (je podporovan pouze JPEG)
	*/
	const LNG_WRONG_TYPE = "Wrong type of image!";

	/**
	* @var string Chybova hlaska pro prilis velky (datove) obrazek
	**/
	const LNG_WRONG_SIZE = "Filesize is too big!";
 
	/**
	* Set array self::$changeChar.
	* @see Piko::$changeChar
	* @param array
	* @return void
	*/
	public static function setChangeChar($char) {
		self::$changeChar = $char;
	}

	/**
	* Get array self::$changeChar.
	* @see Piko::$changeChar
	* @return array
	*/
	public static function getChangeChar() {
		return self::$changeChar;
	}

	/**
	* Nastavi adresar pro nahravane obrazky.
	* @param string Nazev adresare
	*/
	public static function setDirectory($dir) {
		self::$directory = $dir;
	}
	
	/**
	* This method changes special characters in string. The characters are declared in array changeChar.
	* @see Piko::$changeChar
	* @param string Input string.
	* @return string
	*/
	protected function changeChar($string) {
		return strTr($string,self::$changeChar);
	}
    

	/**
	* Return name of last saved image.
	* @return string
	*/
	public static function getLast() {
		return self::$imgName;
	}

	/**
	* Return path of last saved image.
	* @return string
	*/
	public static function getPathOfLast() {
		return self::$directory.self::$imgName;
	}

	/**
	* Get size in bytes of last saved image.
	* @return int
	*/
	public function getSizeOfLast() {
		return filesize(self::getPathOfLast());
	}

	/**
	* This method controls type of image from source and load it to $this->image. Paramater $img is file array (for example $_FILSE[name]).
	* @param string Image name.
	* @return boolean      
	*/
	protected static function load($imgName) {
		try {
			$end = array('jpg','jpeg','png');
			$path = getImageSize($imgName);
			if ((strPos(strToLower($imgName),$end[0]) or strPos(strToLower($imgName),$end[1]))) {
				self::$imgType = "jpeg";           
			}
			else if (strPos(strToLower($imgName),$end[2])) {
				self::$imgType = "png";
			}
			else {
				throw new Exception(self::$lngWrongType);
			}
			if ((filesize($imgName) > self::$imgMaxSize) and (self::$imgMaxSize != 0)) throw new Exception(self::$lngWrongSize);
			self::$imgWidth = $path[0];
			self::$imgWidthOrg = $path[0];
			self::$imgHeight = $path[1];
			self::$imgHeightOrg = $path[1];    
			self::$imgName = $imgName;
			return TRUE;
		}
		catch(Exception $e) {
			echo $e->getMessage();
			return FALSE;
		}
	} 
 
	/**
	* Save image.
	* @param string Name of saved image. If method "save" is call without parameter, saved image has name of original image.
	* @return string Name of saved image.
	*/ 
	public static function save($imgName = NULL) {
		if (!$imgName) {
			$imgName = self::$imgName;
		}
		$imgName = self::$changeChar($imgName);            
		$out = ImageCreateTrueColor(self::$imgWidth,self::$imgHeight);
		$source = ImageCreateFromJpeg(self::$imgName);
		ImageCopyResized ($out,$source,0,0,0,0,self::$imgWidth,self::$imgHeight,$this->imgWidthOrg,$this->imgHeightOrg);
		ImageJpeg ($out, $this->directory.$this->imgPrefix.$imgName, 50);
		chmod (self::$directory.self::$imgPrefix.$imgName,self::$chmod);
		ImageDestroy($out);
		ImageDestroy($source);
		self::$imgName = $imgName;
		return $imgName;             
         } 
 
	/**
	* This method controls size of image and if it is wrong, it will change it.
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
	* When you use object "Ico", you will use method "work". Parameter $img is image you want to save.
	* Parameter $name is name of saved file. If method is called without parameter $name, saved file will be called as original image.
	* @param FILES_array
	* @param string Image name without end piece.
	* @return boolean
	* @example work.php   
	*/
	public static function work($img,$name = NULL) {
		try { 
			if (move_uploaded_file($img[tmp_name],"./".$img[name]) != TRUE) {
				throw new Exception;
			}
			if (self::load($img[name]) != TRUE) {
				throw new Exception;
			}
			self::resize();
			self::save($name);             
			if (($name != $img[name]) or (self::$directory != "") or (self::$imgPrefix != "")) { unlink($img[name]); }
			return TRUE;
		}
		catch(Exception $e) {
			if ($img) { unlink($img[name]); }    
			return FALSE;          
		}
	}
}
?>

