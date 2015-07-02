<?php
defined('INI_FILE') or define('INI_FILE', 'dataModel.ini');
require_once 'AbstractDAO.php';
require_once 'DocumentBuilder.php';

class SqlDAO extends AbstractDAO {

	private $ini_array = array();

	public function __construct() {
		$this->ini_array = parse_ini_file(INI_FILE);
    }

    private function getStatement($sqlquery) {
		$PDO = new PDO(
			$this->ini_array[SQLDataModel::DB_DSN_KEY],
			$this->ini_array[SQLDataModel::DB_USER_KEY]
		);
		$PDO->setAttribute(
			PDO::ATTR_ERRMODE,
			PDO::ERRMODE_EXCEPTION
		);
		
		$statement = $PDO->prepare($sqlquery);
		$PDO = NULL;
		return $statement;
	}

	public function getTitles() {
		$statement = getStatement("SELECT link.link FROM link");
		if ($statement->execute()) {
			$statement->bindColumn(1, $title);
			$resultList = array();
			while ($row = $statement->fetch(PDO::FETCH_BOUND)) {
				array_push($resultList, $title);    
			}
			$statement->closeCursor();
			return $resultList;
		}
		return NULL;
	}

	public function getDocByTitle($title) {
		$statement = getStatement('SELECT link.link, page.title, page.mime, page.text FROM page, link WHERE page.id=link.page_id and link.link =\'' + $title + '\'');
		if ($statement->execute()) {
			$statement->bindColumn(1, $link);
			$statement->bindColumn(2, $title);
			$statement->bindColumn(3, $mime);
			$statement->bindColumn(4, $content);
			$resultList = array();
			while ($row = $statement->fetch(PDO::FETCH_BOUND)) {
				$docBuilder = new DocumentBuilder();
				$doc = $docBuilder.setMime($mime)
					.setTitle($title)
					.setContent($content).build();
				array_push($resultList, $doc);
			}
			$statement->closeCursor();
			return $resultList;
		}
		return NULL;
	}
}
