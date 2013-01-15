<?php
class Paginate {
	protected $results;
	protected $results_per_page;
	protected $url_parameter;

	public function __construct($results = array(), $results_per_page = 20, $url_parameter = 'page') {
		$this->results = $results;
		$this->results_per_page = $results_per_page;
		$this->url_parameter = $url_parameter;
	}

	public function getResults() {
		$result = array();

		for($i = $this->currentPageFirstResultNumber() - 1; $i < $this->currentPageLastResultNumber(); $i++) {
			$result[] = $this->results[$i];
		}

		return $result;
	}

	public function nextPageExists() {
		return $this->resultCount() > $this->currentPageLastResultNumber();
	}

	public function prevPageExists() {
		return $this->currentPageFirstResultNumber() > 1;
	}

	public function lastPageURL() {
		return $this->pageNumberURL($this->pageCount());
	}

	public function nextPageURL() {
		if($this->nextPageExists()) {
			return $this->pageNumberURL($this->currentPage() + 1);
		}
		else {
			return '';
		}
	}

	public function currentPageURL() {
		return $this->pageNumberURL($this->currentPage());
	}

	public function prevPageURL() {
		if($this->prevPageExists()) {
			return $this->pageNumberURL($this->currentPage() - 1);
		}
		else {
			return '';
		}
	}

	public function firstPageURL() {
		return $this->pageNumberURL(1);
	}

	public function pageNumberURL($page = 1) {
		$pageURL = substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['QUERY_STRING']) * -1);

		if(strpos($_SERVER['QUERY_STRING'], $this->url_parameter.'=') === 0) {
			return $pageURL . str_replace_first($this->url_parameter.'='.$_GET[$this->url_parameter], $this->url_parameter.'='.$page, $_SERVER['QUERY_STRING']);
		} elseif(strpos($_SERVER['QUERY_STRING'], '&'.$this->url_parameter.'=') !== FALSE) {
			return $pageURL . str_replace('&'.$this->url_parameter.'='.$_GET[$this->url_parameter], '&'.$this->url_parameter.'='.$page, $_SERVER['QUERY_STRING']);
		} else {
			return $pageURL . $_SERVER['QUERY_STRING'].'&'.$this->url_parameter.'='.$page;
		}
	}

	public function resultCount() {
		return count($this->results);
	}

	public function currentPageResultCount() {
		return $this->currentPageLastResultNumber() - $this->currentPageFirstResultNumber() + 1;
	}

	public function currentPageFirstResultNumber() {
		return $this->results_per_page * $this->currentPage() - $this->results_per_page + 1;
	}

	public function currentPageLastResultNumber() {
		return min($this->resultCount(), $this->results_per_page * $this->currentPage());
	}

	public function pageCount() {
		return ceil($this->resultCount() / $this->results_per_page);
	}

	public function paginationNecessary() {
		return $this->pageCount() > 1;
	}

	public function currentPage() {
		return ((isset($_GET[$this->url_parameter]) && is_numeric(intval($_GET[$this->url_parameter])) && intval($_GET[$this->url_parameter]) > 0) ? intval($_GET[$this->url_parameter]) : 1);
	}
}
?>
