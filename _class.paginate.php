<?php
class Paginate {
	protected $results;
	protected $results_per_page;
	protected $url_parameter;
	protected $links_in_each_direction;

	public function __construct($results = array(), $results_per_page = 20, $url_parameter = 'page') {
		$this->results = $results;
		$this->results_per_page = $results_per_page;
		$this->url_parameter = $url_parameter;
		$this->links_in_each_direction = 3;
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
		$pageURL = $_SERVER['SCRIPT_NAME'];

		if(strlen($_SERVER['QUERY_STRING']) === 0) {
			return $pageURL . '?' . $this->url_parameter.'='.$page;
		} elseif(strpos($_SERVER['QUERY_STRING'], $this->url_parameter.'=') === 0) {
			return $pageURL . '?' . str_replace_first($this->url_parameter.'='.$_GET[$this->url_parameter], $this->url_parameter.'='.$page, $_SERVER['QUERY_STRING']);
		} elseif(strpos($_SERVER['QUERY_STRING'], '&'.$this->url_parameter.'=') !== FALSE) {
			return $pageURL . '?' . str_replace('&'.$this->url_parameter.'='.$_GET[$this->url_parameter], '&'.$this->url_parameter.'='.$page, $_SERVER['QUERY_STRING']);
		} else {
			return $pageURL . '?' . $_SERVER['QUERY_STRING'].'&'.$this->url_parameter.'='.$page;
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

	public function getMetaLinks() {
		$result = '';

		if($this->prevPageExists()) {
			$result .= '<link rel="prev" href="'.$this->prevPageURL().'" />';
		}

		if($this->nextPageExists()) {
			$result .= '<link rel="next" href="'.$this->nextPageURL().'" />';
		}

		return $result;
	}

	public function getLinks() {
		$each_direction = $this->links_in_each_direction;
		$current_page = $this->currentPage();
		$page_count = $this->pageCount();
		$prev_links = array();
		$current_link = '<span class="current_page">'.$current_page.'</span>';
		$next_links = array();
		$separator = '<span class="separator">...</span>';

		for($i = $each_direction; $i > 0; $i--) {
			if($current_page - $i > 1) {
				$prev_links[] = $current_page - $i;
			}
		}

		for($i = 1; $i <= $each_direction; $i++) {
			if($current_page + $i < $page_count) {
				$next_links[] = $current_page + $i;
			}
		}

		ob_start();

		if($this->prevPageExists()) {
			?> <a href="<?php echo $this->firstPageURL(); ?>">1</a> <?php

			if($prev_links[0] > 2) {
				echo $separator;
			}

			foreach($prev_links as $page_num) {
				?> <a href="<?php echo $this->pageNumberURL($page_num); ?>"><?php echo $page_num; ?></a> <?php
			}
		}

		echo $current_link;

		if($this->nextPageExists()) {
			foreach($next_links as $page_num) {
				?> <a href="<?php echo $this->pageNumberURL($page_num); ?>"><?php echo $page_num; ?></a> <?php
			}

			if($next_links[count($next_links) - 1] < $page_count - 1 && $current_page < $page_count - 1) {
				echo $separator;
			}

			?> <a href="<?php echo $this->lastPageURL(); ?>"><?php echo $page_count; ?></a> <?php
		}

		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}
}
?>
