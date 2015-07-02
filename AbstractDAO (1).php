<?php

abstract class AbstractDAO {

	abstract public function getTitles();

	abstract public function getDocByTitle($title);

	abstract public function first();

    abstract public function getFirstResult();

    abstract public function getLastFirstResult();

    abstract public function getResultList();

    abstract public function getMaxResults();

    abstract public function getNextFirstResult();

    abstract public function getPageCount();

    abstract public function getPreviousFirstResult();

    abstract public function getResultCount();

    abstract public function last();

    abstract public function next();

    abstract public function previous();

    abstract public function setFirstResult($firstResult);

    abstract public function setMaxResults($maxResults);

    abstract public function isNextExists();

    abstract public function isPreviousExists();

} 
