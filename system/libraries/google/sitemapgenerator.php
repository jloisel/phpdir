<?php 

abstract class siteMapMain {
	protected $XML = null;
	protected $filename = 'cache/sitemap.xml.gz';
	protected $inputEncoding = 'ISO-8859-15';
	protected $GZCompressionLevel = null;
	protected $NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';
	protected $moreNS = array();
	protected $XMLStylesheets = array();
	protected $position = 0;
	protected $i=0;
	protected $NSContent = array();


	/**
	 * Constructeur
	 *
	 * @param string $file fichier à charger
	 */
	public function __construct($file = null) {
		if($file) {
			if(!is_readable($file)) {
				throw new Exception('Can not load ' . $file);
				die;
			}
			$this->filename = $file;
		}
	}

	/**
	 * Modifie l'encodage d'entrée
	 *
	 * @param string $encoding encodage
	 */
	public function setInputEncoding($encoding) {
		$this->inputEncoding = strtoupper($encoding);
	}

	/**
	 * Fixe le niveau de compression GZIP 0 - 10
	 *
	 * @param int $level niveau de compression
	 */
	public function setGZCompressionLevel($level) {
		//return true;
		$this->GZCompressionLevel = (int)$level;
	}

	/**
	 * Ajoute un espace de nom
	 *
	 * @param string $name nom
	 * @param string $schema schéma de l'espace de nom
	 */
	public function addNamespace($name, $schema) {
		$name = trim($name);
		$schema = trim($schema);
		if(empty($name) || empty($schema)) {
			throw new Exception('Bad NameSpace');
			return false;
		}
		$this->moreNS[$name] = $schema;
	}


	public function addCSSStylesheet($url) {
		$this->XMLStylesheets[] = array('url'=>$url,'type'=>'text/css');
	}

	public function addXSLStylesheet($url) {
		$this->XMLStylesheets[] = array('url'=>$url,'type'=>'text/xsl');
	}

	protected function makeFile($file, $data) {
		if((int)$this->GZCompressionLevel !== 0) {
			if(!extension_loaded('zlib')) {
				throw new Exception('Unable to find zlib extension');
				return false;
			}
			//$data = $this->compress($data, (int)$this->GZCompressionLevel);
			$mode = 'w' . (int)$this->GZCompressionLevel;
			if(!$zp = @gzopen($file, $mode)) {
				throw new Exception('Unable to create/update GZIP sitemap file : '.$file);
				return false;
			}
			gzwrite($zp, $data);
			gzclose($zp);
		} else {
			if(!@file_put_contents($file, $data)) {
				throw new Exception('Unable to create/update sitemap file : '.$file);
				return false;
			}
		}
		return true;
	}



	protected function compress($data, $level=0) {

		if(!(int)$level) {
			return $data;
		}
		return gzcompress($data, (int)$level);
	}

	protected function makeUrl($loc) {
		$loc = htmlspecialchars($loc, ENT_QUOTES, $this->inputEncoding);
		//$loc = urlencode($loc);
		if($this->inputEncoding !== 'UTF-8') {
			return utf8_encode($loc);
		}
		return $loc;
	}

	protected function isW3CCoolDate($date) {
		$mask = '`^\d{4}\-\d{2}\-\d{2}`';
		if(!preg_match($mask, $date)) {
			return false;
		}
		return true;
	}

	protected function makeXMLDeclaration() {
		$r  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		foreach($this->XMLStylesheets as $s) {
			$r  .= '<?xml-stylesheet href="'.$this->makeUrl($s['url']).'" type="'.$s['type'].'"'."\n";
		}
		return $r;
	}

	public function getPosition() {
		return $this->position;
	}

	public function addURLContent($ns, $name,$value='', $attr=array()) {
		$this->NSContent[$this->getPosition()][$ns][$name] = array('value'=>$value, 'attr'=>$attr);
	}
}// end class

// ----------------------------------------------------------------------------------

class siteMapIndexGenerator extends siteMapMain {

	private $siteMapIndex = array();

	/**
	 * Constructeur
	 *
	 * @param string $file fichier index sitemap à charger (Non implémenté)
	 */
	public function __construct($file = null) {
		parent::__construct($file);
	}


	/**
	 * Ajoute l'URL d'un plan sitemap à l'index
	 *
	 * @param string $loc URL du plan sitemap
	 * @param string $lastmod Date de dernière modification
	 * @return bool
	 */
	public function addIndex($loc, $lastmod = null) {
		$loc = trim($loc);
		if(empty($loc)) {
			throw new Exception('Loc can not be empty');
			return false;
		}
		if(substr(strtolower($loc), 0 , 7) !== 'http://') {
			//throw new Exception('Loc est invalide');
			return false;
		}
		if($lastmod && !$this->isW3CCoolDate($lastmod)) {
			throw new Exception('Invalid format for lastmod');
			return false;
		}
		$this->siteMapIndex[] = array(
		'loc' => $loc,
		'lastmod' => $lastmod
		) ;
		$this->position = count($this->siteMapIndex)-1;
		return true;
	}

	/**
	 * Construit le document XML
	 *
	 * @return string XML
	 */
	public function makeXML() {
		$r  = $this->makeXMLDeclaration();
		$r .= '<sitemapindex'."\n";
		$r .= "\t".'xmlns="'.$this->NS.'"';
		foreach($this->moreNS as $n => $s) {
			$r .= "\n\t".'xmlns:'.htmlspecialchars($n).'="'.htmlspecialchars($s).'"';
		}
		$r .= '>'."\n";
		$c = count($this->siteMapIndex);
		if($c === 0 || $c > 1000) {
			throw new Exception('Invalid sitemap count : ' . $c);
			return false;
		}
		foreach($this->siteMapIndex as $v) {
			$r .= $this->makeSiteMapTag($v['loc'], $v['lastmod']);
		}
		$r .= '</sitemapindex>';
		return $this->XML = $r;
	}


	/**
	 * Ecrit le document XML dans un fichier
	 *
	 * @param string $file Optionnel. Nom du fichier de destination
	 * @return bool
	 */
	public function write($file = null) {
		if(!$file) {
			$file = $this->filename;
		}
		if(!$this->XML) {
			$this->makeXML();
		}
		return $this->makeFile($file, $this->XML);
	}


	/**
	 * Affiche les données XML ou les renvoie dans une chaine
	 *
	 * @param bool $return
	 * @return output
	 */
	public function output($return = false) {
		if(!$this->XML) {
			$this->makeXML();
		}
		if($return) {
			return $this->XML;
		}
		if(headers_sent()) {
			throw new Exception('Can not send headers');
			return false;
		}
		//header("Content-Encoding: gzip");
		header('content-type: text/xml');
		echo $this->XML;
	}



	private function makeSiteMapTag($loc,$lastmod) {
		$r = '<sitemap>'."\n";
		$r .= "\t".'<loc>' . $this->makeUrl($loc) . '</loc>'."\n";
		if($lastmod) {
			$r .= "\t".'<lastmod>' . $lastmod . '</lastmod>'."\n";
		}
		$r .= '</sitemap>'."\n";
		return $r;
	}

}// end class

// -------------------------------------------------------------------------------------

class siteMapGenerator extends siteMapMain {

	private $siteMapURL = array();
	private $changeFreqControl = array(
	'always',
	'hourly',
	'daily',
	'weekly',
	'monthly',
	'yearly',
	'never'
	);


	/**
	 * Constructeur
	 *
	 * @param string $file fichier à charger
	 */
	public function __construct($file = null) {
		parent::__construct($file);
	}

	public function addURL($loc, $lastmod = null, $changefreq = null, $priority = null) {
		if(empty($loc)) {
			throw new Exception('Loc est absent');
			return false;
		}
		if(substr(strtolower($loc), 0 , 7) !== 'http://') {
			//throw new Exception('Loc est invalide');
			return false;
		}
		if($lastmod && !$this->isW3CCoolDate($lastmod)) {
			throw new Exception('Invalid format for lastmod');
			return false;
		}
		if($changefreq && !in_array($changefreq, $this->changeFreqControl)) {
			throw new Exception('Invalid format for changefreq');
			return false;
		}
		if($priority && (!is_numeric($priority) || $priority<0 || $priority>1)) {
			throw new Exception('Invalid format for priority 0.0 > 1.0');
			return false;
		}elseif($priority) {
			$priority = sprintf('%0.1f',$priority);
		}
		$this->siteMapURL[] = array(
		'loc' => $loc,
		'lastmod' => $lastmod,
		'changefreq' => $changefreq,
		'priority' => $priority
		) ;
		$this->position = count($this->siteMapURL)-1;
		return true;
	}

	/**
	 * Construit le document XML
	 *
	 * @return string XML
	 */
	public function makeXML() {
		$r  = $this->makeXMLDeclaration();
		$r .= '<urlset'."\n";
		$r .= "\t".'xmlns="'.$this->NS.'"';
		foreach($this->moreNS as $n => $s) {
			$r .= "\n\t".'xmlns:'.htmlspecialchars($n).'="'.htmlspecialchars($s).'"';
		}
		$r .= '>'."\n";
		$c = count($this->siteMapURL);
		if($c === 0 || $c > 50000) {
			throw new Exception('Invalid sitemap count');
			return false;
		}
		foreach($this->siteMapURL as $v) {
			$r .= $this->makeURLTag($v['loc'], $v['lastmod'], $v['changefreq'], $v['priority']);
			$this->i++;
		}
		$r .= '</urlset>'."\n";
		return $this->XML = $r;
	}

	/**
	 * Ecrit le document XML dans un fichier
	 *
	 * @param string $file Optionnel. Nom du fichier de destination
	 * @return bool
	 */
	public function write($file = null) {
		if(!$file) {
			$file = $this->filename;
		}
		if(!$this->XML) {
			$this->makeXML();
		}
		$this->makeFile($file, $this->XML);
	}

	/**
	 * Affiche les données XML ou les renvoie dans une chaine
	 *
	 * @param bool $return
	 * @return output
	 */
	public function output($return = false) {
		if(!$this->XML) {
			$this->makeXML();
		}
		if($return) {
			return $this->XML;
		}
		if(headers_sent()) {
			throw new Exception('Can not send headers');
			return false;
		}
		//header("Content-Encoding: gzip");
		header('content-type: text/xml');
		echo $this->XML;
	}

	private function makeURLTag($loc,$lastmod,$changefreq,$priority) {
		$r = '<url>'."\n";
		$r .= "\t".'<loc>' . $this->makeUrl($loc) . '</loc>'."\n";
		if($lastmod) {
			$r .= "\t".'<lastmod>' . $lastmod . '</lastmod>'."\n";
		}
		if($changefreq) {
			$r .= "\t".'<changefreq>' . $changefreq . '</changefreq>'."\n";
		}
		if($priority) {
			$r .= "\t".'<priority>' . $priority . '</priority>'."\n";
		}
		if(isset($this->NSContent[$this->i])) {
			foreach($this->NSContent[$this->i] as $k=>$v) {
				$r .= "\t".'<'.$k.':';
				foreach($v as $l =>$x) {
					$r .= $l;
					$value = trim($x['value']);
					if(empty($value)) {
						$r .= ' />'."\n";
					}
				}
			}
			//echo $this->getPosition();
			//print_r($this->NSContent[$this->i]);
			//die;
		}
		$r .= '</url>'."\n";
		return $r;
	}
}// end class

?>