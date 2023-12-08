<?php
/*
	Plugin Name: Meo Avis
	Plugin URI: https://www.meosis.fr/
	Description: Extension pour afficher les avis.
	Version: 2.0.4.10.19
	Author: Meosis
	Author URI: https://www.meosis.fr/
*/

class MEO_Avis{

	// Constructor
	function __construct() {
		#add_action( 'init', array( $this, 'meo_avis_add_menu' ));
#		register_activation_hook( __FILE__, array( $this, 'wpa_install' ) );
#		register_deactivation_hook( __FILE__, array( $this, 'wpa_uninstall' ) );
#		add_action( 'manage_comments_custom_column' , array( $this, 'custom_columns' ), 10, 2 );
		add_action('load-edit-comments.php', array( $this, 'wpse64973_load' ) );
		add_action( 'manage_comments_custom_column' , array( $this, 'custom_columns' ), 10, 2 );

		add_filter('manage_comments_columns', array( $this, 'custom_columns_head' ) );

		add_action( 'comment_form_logged_in_after', array( $this, 'additional_fields' ) );
		add_action( 'comment_form_before_fields', array( $this, 'additional_fields' ) );
		add_action( 'comment_post', array( $this, 'save_comment_meta_data' ) );
		add_filter( 'comment_text', array( $this, 'modify_comment' ) );

		add_action( 'add_meta_boxes_comment', array( $this,'extend_comment_add_meta_box') );
		add_action( 'edit_comment', array( $this,'extend_comment_edit_metafields') );
	}

	function extend_comment_add_meta_box() {
		add_meta_box( 'postbox-container-3', 'Modifier la note', array( $this,'extend_comment_meta_box'), 'comment', 'normal', 'high' );
	}

	function extend_comment_meta_box ( $comment ) {
		echo '<p class="comment-form-rating">'.
			'<label for="rating">'. __('Note') . ' </label>
			<span class="commentratingbox">';
		if( $commentrating = get_comment_meta( $comment->comment_ID, 'rating', true ) ) {
			$stars = '';
			for ($i=1; $i <= 5; $i++) { 
				if ($i<=($commentrating)) {
					$stars .= "<span class='commentrating' style='display:inline-block;'><label for='rating-$i'><i class='fas fa-star'></i></label><input type='radio' name='rating' id='rating-$i' value='". $i ."' checked='checked' style='display:none;'/></span>";
				}
				else {
					$stars .= "<span class='commentrating' style='display:inline-block;'><label for='rating-$i'><i class='far fa-star'></i></label><input type='radio' name='rating' id='rating-$i' value='". $i ."' style='display:none;'/></span>";
				}
			}
			$commentrating = '<p class="comment-rating"> '.$stars.'</p>'; 
			echo $commentrating ;
		} else {
			for ($i=1; $i <= 5; $i++) { 
				$stars .= "<span class='commentrating' style='display:inline-block;'><label for='rating-$i'><i class='far fa-star'></i></label><input type='radio' name='rating' id='rating-$i' value='". $i ."' style='display:none;'/></span>";
			}
			$commentrating = '<p class="comment-rating"> '.$stars.'</p>';
			echo $commentrating;
		}

		?>
		<script type="text/javascript">
			(function($){
				$(document).ready(function(){
					$('.commentrating').on({
						mouseover: function() {
							$(this).find('.far.fa-star').removeClass('far').addClass('fas');
							$(this).prevAll().find('.far.fa-star').removeClass('far').addClass('fas');
							$(this).nextAll().find('.fas.fa-star').removeClass('fas').addClass('far');
						},
						mouseout: function() {
							if ($('.commentrating').find('input:checked').length > 0) {
								rV = $('.commentrating').find('input:checked').val();
								$('.commentrating').nextUntil($('.commentrating').eq(rV)).find('.far.fa-star').removeClass('far').addClass('fas');
								$('.commentrating').eq(rV-1).nextAll().find('.fas.fa-star').removeClass('fas').addClass('far');
							} else {
								$(this).parent().find('.far.fa-star').removeClass('far').addClass('fas');
							}
						}
					});

				});
			})(jQuery);
		</script>
	<?php }

	function extend_comment_edit_metafields( $comment_id ) {
		if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != ’) ):
		  $rating = wp_filter_nohtml_kses($_POST['rating']);
		  update_comment_meta( $comment_id, 'rating', $rating );
		  else :
		  delete_comment_meta( $comment_id, 'rating');
		  endif;
	}

	function additional_fields() {
		echo '<p class="comment-form-rating">'.
			'<label for="rating">'. __('Note') . ' <span class="required">*</span></label>
			<span class="commentratingbox">';

		//Current rating scale is 1 to 5. If you want the scale to be 1 to 10, then set the value of $i to 10.
		for( $i=1; $i <= 5; $i++ )
		echo "<span class='commentrating' style='display:inline-block;'><label for='rating-$i'><i class='far fa-star'></i></label><input type='radio' name='rating' id='rating-$i' value='$i' style='display:none;'/></span>";

		echo'</span></p>';
		?>
		<script type="text/javascript">
			(function($){
				$(document).ready(function(){
					$('.commentrating').on({
						mouseover: function() {
							$(this).find('.far.fa-star').removeClass('far').addClass('fas');
							$(this).prevAll().find('.far.fa-star').removeClass('far').addClass('fas');
							$(this).nextAll().find('.fas.fa-star').removeClass('fas').addClass('far');
						},
						mouseout: function() {

							if ($('.commentrating').find('input:checked').length > 0) {
								rV = $('.commentrating').find('input:checked').val();
								$('.commentrating').nextUntil($('.commentrating').eq(rV)).find('.far.fa-star').removeClass('far').addClass('fas');
								$('.commentrating').eq(rV-1).nextAll().find('.fas.fa-star').removeClass('fas').addClass('far');
							} else {
								$(this).parent().find('.far.fa-star').removeClass('far').addClass('fas');
							}
						}
					});

				});
			})(jQuery);
		</script>

		<?php
	}

	function save_comment_meta_data( $comment_id ) {
		if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') )
			$rating = wp_filter_nohtml_kses($_POST['rating']);
			add_comment_meta( $comment_id, 'rating', $rating );
	}

	function modify_comment( $text ){
		if( $commenttitle = get_comment_meta( get_comment_ID(), 'title', true ) ) {
			$commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';
			$text = $commenttitle . $text;
		} 
		if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
			$stars = '';
			for ($i=1; $i <= 5; $i++) { 
				if ($i<=($commentrating)) {
					$stars .= '<i class="fas fa-star"></i>';
				}
				else {
					$stars .= '<i class="far fa-star"></i>';
				}
			}
			$commentratingt = '<p class="comment-rating"> '.$stars.'</p>'; 
			$text = $commentratingt . $text ;
			return $text;
		} else {
			$commentratingt = '<p class="comment-rating"><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></p>';
			$text = $commentratingt . $text ; 
			return $text;
		}
	}

	function wpse64973_load()
	{
		$screen = get_current_screen();
		add_filter("manage_{$screen->id}_columns", array( $this, 'wpse64973_add_columns' ) );
	}


	function wpse64973_add_columns($cols)
	{
		unset($cols);
		$cols['author'] = __('Author', '');
		$cols['rating'] = __('Note', 'twentyseventeen');
		$cols['comment'] = __('Comment', 'twentyseventeen');
		$cols['date'] = __('Submitted On', 'twentyseventeen');
		return $cols;
	}

	function custom_columns_head($columns) {
		unset($columns);
		$columns['author'] = 'Avatar';
		$columns['rating'] = 'Note';
		$columns['title'] = 'Auteur';
		$columns['date'] = 'Date';
		return $columns;
	}

	function custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'rating':
			if($t = get_comment_meta(get_comment_ID(), 'rating', true))
			{
				$stars = '';
				for ($i=1; $i <= 5; $i++) { 
					if ($i<=($t)) {
						$stars .= '<i class="fas fa-star"></i>';
					}
					else {
						$stars .= '<i class="far fa-star"></i>';
					}
				}
				echo $stars;
			}
			else
			{
				$stars .= '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
				echo $stars;
			}
			break;
		}
	}

}

new MEO_Avis();

function meo_recent_comments($atts) {
	ob_start();
	$atts = shortcode_atts(array(
	   'number' => $atts['nombre'],
	   'post_id' => $atts['id'],
	   'status' => 'approve',
	), $atts);
	$comments_query = new WP_Comment_Query();
	$comments = $comments_query->query( $atts );
	$comm = '';
    $nav = '';
    $comm .= '<div class="front-page-avis-content">';
	if ( $comments ) : foreach ( $comments as $k => $comment ) :
		$rtng = get_comment_meta( $comment->comment_ID, 'rating', true );
		$stars = '';
		for ($i=1; $i <= 5; $i++) { 
			if ($i<=($rtng)) {
				$stars .= '<i class="fas fa-star"></i>';
			}
			else {
				$stars .= '<i class="far fa-star"></i>';
			}
		}
        $comm .= '<div class="meo-avis">';
	        $comm .= '<div class="meo-avis-item">';
	            $comm .= '<div class="meo-avis-author">'.get_comment_author( $comment->comment_ID ) .'</div>';
	            $comm .= '<div class="meo-avis-date">'.get_comment_date('d / m / Y', $comment->comment_ID).'</div>';
	            $comm .= '<div class="meo-avis-rating"> '.$stars.'</div>';
				$comm .= '<div class="meo-avis-content">' . strip_tags( apply_filters( 'get_comment_text', $comment->comment_content ) ) . '</div>';
	        $comm .= '</div>';    
			// $comm .= '<figure class="front-page-avis-avatar">'. get_avatar( $comment, $atts['avatar_size'] ).'</figure>';
		$comm .= '</div>';
	endforeach; else :
		$comm .= 'Aucun avis.';
	endif;
    $comm .='</div>';
    // echo $comm;
	echo sprintf( apply_filters( 'meo_recent_comments', $comm, $atts['nombre'], $atts['avatar_size'] ),
		$comm, $nb_comments, $avatar_size);
	return ob_get_clean();
}
add_shortcode('recent_comments', 'meo_recent_comments');
/*
function script_footer() { ?>
    <script type="text/javascript" src="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>
<?php }
add_action( 'wp_footer', "script_footer" );

function script_header() { ?>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css"> 
<?php }
add_action( 'wp_head', "script_header" );
*/
class WPSE_Walker_Comment extends Walker_Comment {

	public function comment( $comment, $depth, $args ) {
		?>
		<div class="avis-item" id="comment-<?php comment_ID(); ?>"><div class="avis-wrap">
			<figure class="avis-avatar">
				<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			</figure>
			<?php
			if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
				$stars = '';
				for ($i=1; $i <= 5; $i++) { 
					if ($i<=($commentrating)) {
						$stars .= '<i class="far fa-star"></i>';
					}
					else {
						$stars .= '<i class="fas fa-star"></i>';
					}
				}
				$commentratingt = '<p class="avis-rating"> '.$stars.'</p>';
				echo $commentratingt;
			}
			?>
			<div class="avis-text"><?php comment_text( get_comment_id(), $args ); ?></div>
			<p class="avis-author">
				<?php printf( __( '%1$s<br/>le %2$s' ),
						sprintf( '<cite class="fn">%s</cite>', get_comment_author_link( $comment ) ),
						get_comment_date( '', $comment )
					);
					echo '<br/>';
					edit_comment_link( __( 'Edit' ), '<i class="fa fa-edit"></i>', '' );
				?>
			</p>
			<?php if ( '0' == $comment->comment_approved ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em>
			<br />
			<?php endif; ?>
			<?php
			comment_reply_link( array_merge( $args, array(
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
				'before'    => '<div class="reply">',
				'after'     => '</div>'
			) ) );
			?>
		</div></div>
	<?php
	}
}

add_action('admin_head', 'custom_css_back');

function custom_css_back() {
  echo '
  <link rel="stylesheet" href="../wp-content/plugins/elementor/assets/lib/font-awesome/css/fontawesome.min.css" type="text/css" media="all">
  <link rel="stylesheet" href="../wp-content/plugins/elementor/assets/lib/font-awesome/css/all.min.css" type="text/css" media="all">
  <link rel="stylesheet" href="../wp-content/plugins/elementor/assets/lib/font-awesome/css/v4-shims.min.css" type="text/css" media="all">
  <link rel="stylesheet" href="../wp-content/plugins/elementor/assets/lib/font-awesome/css/brands.min.css" type="text/css" media="all">
  <link rel="stylesheet" href="../wp-content/plugins/elementor/assets/lib/font-awesome/css/solid.min.css" type="text/css" media="all">
  <script type="text/javascript" src="../wp-content/plugins/elementor/assets/lib/font-awesome/js/v4-shims.min.js"></script>


  <style>
    .rating.column-rating i,
    .comment-rating i{
    	color:#ffcc00;
    	font-family: "Font Awesome 5 Free";
    	font-size:16px;
    	margin:0 2px;
    }
    .widefat .column-comment p {margin: 0 0 6px 0;}
    .widefat .column-comment p.comment-rating {display: none;}
  </style>';
}