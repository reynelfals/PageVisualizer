<?php

abstract class AbstractDAO {

	abstract public function getTitles();
	abstract public function getDocByTitle($title); 

} 
