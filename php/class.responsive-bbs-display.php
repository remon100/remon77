<?php


require_once( dirname( __FILE__ ) .'/class.responsive-bbs.php' );




class Responsive_Bbs_Display Extends Responsive_Bbs {
	
	// pagination addon property
	protected $now_page       = '1';
	protected $all_post_count = 0;
	
	
	// search addon property
	protected $search_word    = '';
	
	
	
	
	// public construct
	public function __construct() {
		
		parent::__construct();
		
	}
	
	
	
	
	// public get_page
	public function get_page() {
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/pagination/get-page.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/pagination/get-page.php' );
		}
		
	}
	
	
	
	
	// public get_search
	public function get_search() {
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/search/get-search.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/search/get-search.php' );
		}
		
	}
	
	
	
	
	// public all_post_count
	public function all_post_count() {
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/pagination/all-post-count.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/pagination/all-post-count.php' );
		}
		
	}
	
	
	
	
	// public search_post_count
	public function search_post_count() {
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/search/search-post-count.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/search/search-post-count.php' );
		}
		
	}
	
	
	
	
	// public html_header
	public function html_header() {
		
		require_once( dirname( __FILE__ ) .'/../html/html-header.html' );
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/edit/edit-css.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/edit/edit-css.php' );
		}
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/response/response-css.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/response/response-css.php' );
		}
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/attachment/attachment-css.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/attachment/attachment-css.php' );
		}
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/pagination/pagination-css.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/pagination/pagination-css.php' );
		}
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/search/search-css.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/search/search-css.php' );
		}
		
	}
	
	
	
	
	// public header
	public function header() {
		
		require_once( dirname( __FILE__ ) .'/../html/header.html' );
		
	}
	
	
	
	
	// public footer
	public function footer() {
		
		$admin_js   = '';
		$overlay_js = '';
		$cookie_js  = '';
		
		
		if ( isset( $_SESSION['responsive_bbs_login'] ) ) {
			$admin_js = PHP_EOL.'<script src="'. $this->dir .'/js/responsive-bbs-admin-js.php"></script>';
		}
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/attachment/overlay-js.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/attachment/overlay-js.php' );
		}
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/write-scroll/jquery-cookie-js.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/write-scroll/jquery-cookie-js.php' );
		} else if ( file_exists( dirname( __FILE__ ) .'/../addon/search/jquery-cookie-js.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/search/jquery-cookie-js.php' );
		}
		
		
		require_once( dirname( __FILE__ ) .'/../html/footer.html' );
		
		
		echo <<<EOM

{$cookie_js}<script src="{$this->dir}/js/responsive-bbs-js.php"></script>{$admin_js}{$overlay_js}
</body>
</html>
EOM;
		
	}
	
	
	
	
	// public bbs_form
	public function bbs_form() {
		
		$hidden_url_no = '';
		$enctype       = '';
		
		
		if ( $this->upload_max_size !== 0 ) {
			$enctype = ' enctype="multipart/form-data"';
		}
		
		
		echo <<<EOM


<form action="{$this->url}" method="post" id="bbs-form" class="form-area"{$enctype}>
EOM;
		
		
		require_once( dirname( __FILE__ ) .'/../html/bbs-form.html' );
		
		
		echo <<<EOM

	<p class="submit"><input type="button" id="write-button" value="書き込む" /><input type="hidden" name="token" value="{$this->token}" />{$hidden_url_no}</p>
</form>
EOM;
		
	}
	
	
	
	
	// public edit_form
	public function edit_form() {
		
		if ( ! isset( $_SESSION['responsive_bbs_login'] ) ) {
			return;
		}
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/edit/edit-form.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/edit/edit-form.php' );
		}
		
	}
	
	
	
	
	// public search_form
	public function search_form() {
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/search/search-form.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/search/search-form.php' );
		}
		
	}
	
	
	
	
	// public bbs_display
	public function bbs_display() {
		
		$stmt = $this->pdo->prepare( "SELECT * FROM rb_post WHERE delegate = 'true' ORDER BY id DESC" );
		$stmt->execute();
		
		while ( $row = $stmt->fetch() ) {
			if ( $row['res'] == '0' ) {
				
				$this->post_display( $row['id'], $row['title'], $row['time'], $row['name'], $row['small'], $row['large'], $row['contents'], $row['addr'] );
				
			} else {
				
				$parent_stmt = $this->pdo->prepare( "SELECT * FROM rb_post WHERE ( id = :id )" );
				$parent_stmt->bindParam( ':id', $row['res'] );
				$parent_stmt->execute();
				
				$parent_row = $parent_stmt->fetch();
				$this->post_display( $parent_row['id'], $parent_row['title'], $parent_row['time'], $parent_row['name'], $parent_row['small'], $parent_row['large'], $parent_row['contents'], $parent_row['addr'] );
				
			}
		}
		
	}
	
	
	
	
	// public bbs_display_pagination
	public function bbs_display_pagination() {
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/pagination/bbs-display-pagination.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/pagination/bbs-display-pagination.php' );
		}
		
	}
	
	
	
	
	// public search_display
	public function search_display() {
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/search/search-display.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/search/search-display.php' );
		}
		
	}
	
	
	
	
	// public post_display
	public function post_display( $id, $title, $time, $name, $small, $large, $contents, $addr ) {
		
		$time_difference = 0;
		$new_label       = '';
		$response_span   = '';
		$delete_span     = '';
		$edit_span       = '';
		$image_html      = '';
		$addr_li         = '';
		
		
		$time_difference = ( strtotime( date( 'Y-m-d H:i:s' ) ) - strtotime( $time ) ) / ( 60 * 60 * 24 );
		if ( (int)$time_difference < (int)$this->new_label_day ) {
			$new_label = '<span class="new-label">NEW</span>';
		}
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/response/response-span.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/response/response-span.php' );
		}
		
		if ( isset( $_SESSION['responsive_bbs_login'] ) ) {
			$delete_span = '<span class="delete" data-delete="'.$id.'">削除する</span>';
			
			if ( file_exists( dirname( __FILE__ ) .'/../addon/edit/parent-span.php' ) ) {
				include( dirname( __FILE__ ) .'/../addon/edit/parent-span.php' );
			}
			
			$addr_li     = PHP_EOL.'		<li>IP: '.$addr.'</li>';
		}
		
		if ( $small !== '' && $large !== '' ) {
			if ( file_exists( dirname( __FILE__ ) .'/../addon/attachment/parent-image.php' ) ) {
				include( dirname( __FILE__ ) .'/../addon/attachment/parent-image.php' );
			}
		}
		
		
		echo <<<EOM


<div class="bbs-post">
	<h2>{$new_label}{$title}{$response_span}{$edit_span}{$delete_span}</h2>
	<ul>
		<li class="post-time">{$time}</li>
		<li>{$name}</li>{$addr_li}
	</ul>
	<p>{$image_html}{$contents}</p>
EOM;
		
		
		$res_stmt = $this->pdo->prepare( "" );
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/response/res-stmt.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/response/res-stmt.php' );
		}
		
		while ( $res_row = $res_stmt->fetch() ) {
			
			$time_difference = 0;
			$new_label       = '';
			$delete_span     = '';
			$edit_span       = '';
			$image_html      = '';
			$addr_li         = '';
			
			$time_difference = ( strtotime( date( 'Y-m-d H:i:s' ) ) - strtotime( $res_row['time'] ) ) / ( 60 * 60 * 24 );
			if ( (int)$time_difference < (int)$this->new_label_day ) {
				$new_label = '<span class="new-label">NEW</span>';
			}
			
			if ( isset( $_SESSION['responsive_bbs_login'] ) ) {
				$delete_span = '<span class="delete" data-delete="'.$res_row['id'].'">削除する</span>';
				
				if ( file_exists( dirname( __FILE__ ) .'/../addon/edit/res-span.php' ) ) {
					include( dirname( __FILE__ ) .'/../addon/edit/res-span.php' );
				}
				
				$addr_li     = PHP_EOL.'		<li>IP: '.$res_row['addr'].'</li>';
			}
			
			if ( $res_row['small'] !== '' && $res_row['large'] !== '' ) {
				if ( file_exists( dirname( __FILE__ ) .'/../addon/attachment/res-image.php' ) ) {
					include( dirname( __FILE__ ) .'/../addon/attachment/res-image.php' );
				}
			}
			
			
			if ( file_exists( dirname( __FILE__ ) .'/../addon/response/res-display.php' ) ) {
				include( dirname( __FILE__ ) .'/../addon/response/res-display.php' );
			}
			
		}
		
		echo PHP_EOL;
		echo '</div>';
		
	}
	
	
	
	
	// public pagination_link
	public function pagination_link() {
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/pagination/pagination-link.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/pagination/pagination-link.php' );
		}
		
	}
	
}

?>