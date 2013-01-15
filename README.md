pagination
==========

I *despise* writing code to handle pagination. Every time I have to do it, I dread it, procrastinate, etc. And then every time I reload during development, I get way more data than I need. And I often decide just to return all results rather than deal with pagination. Not exactly ideal. So I made these two classes, Paginate and PaginationLinks, to take care of it for me.

Example Usage
-------------
```php
$pagination = new Paginate($array_with_all_results);
$pagination_links = new PaginationLinks($pagination);
$current_page_results = $pagination->getResults();
$total_result_count = $pagination->resultCount();

foreach($current_page_results as $result) {
	echo $result;
}

echo $pagination_links;
```
