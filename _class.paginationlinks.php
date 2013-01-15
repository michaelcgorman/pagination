<?php
class PaginationLinks {
	protected $pagination_object;
	protected $each_direction;
	protected $current_page;
	protected $page_count;
	protected $prev_links;
	protected $current_link;
	protected $next_links;

	public function __construct($pagination_object = NULL) {
		$this->pagination_object = $pagination_object;
		$this->each_direction = 3;
		$this->current_page = $this->pagination_object->currentPage();
		$this->page_count = $this->pagination_object->pageCount();
		$this->prev_links = array();
		$this->current_link = '<span class="current_page">'.$this->current_page.'</span>';
		$this->next_links = array();
		$this->separator = '<span class="separator">...</span>';

		for($i = $this->each_direction; $i > 0; $i--) {
			if($this->current_page - $i > 1) {
				$this->prev_links[] = $this->current_page - $i;
			}
		}

		for($i = 1; $i <= $this->each_direction; $i++) {
			if($this->current_page + $i < $this->page_count) {
				$this->next_links[] = $this->current_page + $i;
			}
		}
	}

	public function __toString() {
		ob_start();
?>
	<?php if($this->pagination_object->prevPageExists()) { ?>
		<a href="<?php echo $this->pagination_object->firstPageURL(); ?>">1</a>
		<?php if($this->prev_links[0] > 2) { ?>
			<?php echo $this->separator; ?>
		<?php } ?>
		<?php foreach($this->prev_links as $page_num) { ?>
			<a href="<?php echo $this->pagination_object->pageNumberURL($page_num); ?>"><?php echo $page_num; ?></a>
		<?php } ?>
	<?php } ?>

	<?php echo $this->current_link; ?>

	<?php if($this->pagination_object->nextPageExists()) { ?>
		<?php foreach($this->next_links as $page_num) { ?>
			<a href="<?php echo $this->pagination_object->pageNumberURL($page_num); ?>"><?php echo $page_num; ?></a>
		<?php } ?>
		<?php if($this->next_links[count($this->next_links) - 1] < $this->page_count - 1 && $this->current_page < $this->page_count - 1) { ?>
			<?php echo $this->separator; ?>
		<?php } ?>
		<a href="<?php echo $this->pagination_object->lastPageURL(); ?>"><?php echo $this->page_count; ?></a>
	<?php } ?>
		<?php
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}
}

?>
