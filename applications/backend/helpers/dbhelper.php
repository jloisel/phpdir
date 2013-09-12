<?php

/**
 * Database helper intends to make some 
 * complex requests to database easier.
 * 
 * @author Jerome Loisel
 *
 */
class DbHelper extends AbstractHelper {
	
	/**
	 * Returns a Doctrine_Pager_layout to allow easy 
	 * result pagination.
	 *
	 * @param Doctrine_Query $q
	 * @param integer $currentPage default is 1
	 * @param integer $resultsPerPage default is 10
	 * @param integer $chunkSize default is 5
	 * @param string $url URL containing {%page_number} string
	 * @return Doctrine_Pager_Layout
	 */
	public static function pagined(Doctrine_Query $q,$currentPage=1,$url,$resultsPerPage=10, $chunkSize=5,$separator='&nbsp;') {
		$pager = new Doctrine_Pager_Layout(
		    new Doctrine_Pager(
		        $q,
		        $currentPage,
		        $resultsPerPage
		    ),
		    new Doctrine_Pager_Range_Sliding(array(
		        'chunk' => $chunkSize
		    )),
    		$url.'/{%page_number}'
		);
		
		$pager->setSeparatorTemplate($separator);
		$pager->setSelectedTemplate('<b>[{%page}]</b>');
		return $pager;
	}
	
}

?>