<?php

/* This is a global array of translation strings on front-end */

global $gridlove_translate;

$gridlove_translate = array(
	'no_comments' => array( 'text' => esc_html__( 'Add comment', 'gridlove' ), 'desc' => 'Comment meta data (if zero comments)' ),
	'one_comment' => array( 'text' => esc_html__( '1 comment', 'gridlove' ), 'desc' => 'Comment meta data (if 1 comment)' ),
	'multiple_comments' => array( 'text' => esc_html__( '% comments', 'gridlove' ), 'desc' => 'Comment meta data (if more than 1 comments)' ),
	'views' => array( 'text' => esc_html__( 'views', 'gridlove' ), 'desc' => 'Used in post meta data (number of views)' ),
	'min_read' => array( 'text' => esc_html__( 'min read', 'gridlove' ), 'desc' => 'Used in post meta data (reading time)' ),
	'category' => array('text' => esc_html__('Category - ', 'gridlove'), 'desc' => 'Category title prefix'),
	'tag' => array('text' => esc_html__('Tag - ', 'gridlove'), 'desc' => 'Tag title prefix'),
	'author' => array('text' => esc_html__('Author - ', 'gridlove'), 'desc' => 'Author title prefix'),
	'archive' => array('text' => esc_html__('Archive - ', 'gridlove'), 'desc' => 'Archive title prefix'),
	'search_placeholder' => array('text' => esc_html__('Type here to search...', 'gridlove'), 'desc' => 'Search placeholder text'),
	'search_button' => array('text' => esc_html__('Search', 'gridlove'), 'desc' => 'Search button text'),
	'search_results_for' => array('text' => esc_html__('Search results for - ', 'gridlove'), 'desc' => 'Title for search results template'),
	'newer_entries' => array('text' => esc_html__('Newer Entries', 'gridlove'), 'desc' => 'Pagination (prev/next) link text'),
	'older_entries' => array('text' => esc_html__('Older Entries', 'gridlove'), 'desc' => 'Pagination (prev/next) link text'),
	'previous_posts' => array('text' => esc_html__('Previous', 'gridlove'), 'desc' => 'Pagination (numeric) link text'),
	'next_posts' => array('text' => esc_html__('Next', 'gridlove'), 'desc' => 'Pagination (numeric) link text'),
	'load_more' => array('text' => esc_html__('Load More', 'gridlove'), 'desc' => 'Pagination (load more) link text'),
	'related' => array('text' => esc_html__('You may also like', 'gridlove'), 'desc' => 'Related posts area title'),
	'view_all' => array('text' => esc_html__('View all posts', 'gridlove'), 'desc' => 'View all posts link text in author box'),
	'comment_submit' => array('text' => esc_html__('Submit Comment', 'gridlove'), 'desc' => 'Comment form submit button label'),
	'comment_reply' => array('text' => esc_html__('Reply', 'gridlove'), 'desc' => 'Comment reply label'),
	'404_title' => array('text' => esc_html__('Page not found', 'gridlove'), 'desc' => '404 page title'),
	'404_text' => array('text' => esc_html__('The page that you are looking for does not exist on this website. You may have accidentally mistype the page address, or followed an expired link. Anyway, we will help you get back on track. Why not try to search for the page you were looking for:', 'gridlove'), 'desc' => '404 page text'),
	'content_none' => array('text' => esc_html__('Sorry, there are no posts found on this page. Feel free to contact website administrator regarding this issue.', 'gridlove'), 'desc' => 'Message when there are no posts on archive pages. i.e Empty Category'),
	'content_none_search' => array('text' => esc_html__('No results found. Please try again with a different keyword.', 'gridlove'), 'desc' => 'Message when there are no search results.') 
);

?>