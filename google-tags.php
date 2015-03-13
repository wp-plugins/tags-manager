<?php 

/*
Plugin Name: Tags Manager 
Author: BBKP
Plugin URL: http://bibunkaplan.com
Description: Google Tags Managing Plugin - such as Google Webmaster Tool, Google Analytics, Remarketing(Google Adwords).
Verison: 1.0
Author URL: http://bibunkaplan.com
Domain Path /tags-manager
Text Domain: tags-manager
*/


add_action( 'admin_menu', 'google_tags_admin_menu' ); 


//メニュー
function google_tags_admin_menu() {	

	add_options_page(
		__('Tags Manager', 'my_google_tags'), 
		__('Tags Manager', 'my_google_tags'), 
		'manage_options', 
		'google_tags', 
		'my_google_tags'		
	); 
}


//実装部分
function my_google_tags() {
?>

<div class="wrap">
<h2>Tags Manager by Googles</h2>
<h3>Set Up of Google WebMasterTool, Google Analytics and Google Remarketing </h3>
<form id="google-tags" method="post" action="">
	
	<?php wp_nonce_field( 'google-tabs-nonce-key', 'google-tags' );  ?>
	
	<p><?php echo esc_html(__( 'Google Webmastertool is here', 'google-tags' )); ?>:
	<input type="text" name="google-webmaster-tool" value="<?php echo esc_attr(stripslashes(get_option('google-webmaster-tool'))); ?>" size="105"/></p>

	<p><?php echo esc_html(__( 'Google Analytics is here', 'google-tags' )); ?>:</p>
	<textarea cols="40" rows="6" name="google-analytics"><?php echo esc_textarea(stripslashes(get_option('google-analytics'))); ?></textarea>
	
	<p><?php echo esc_html(__( 'Google Remarketing is here', 'google-tags' )); ?>:</p>
	<textarea cols="40" rows="6" name="google-remarketing"><?php echo esc_textarea(stripslashes(get_option('google-remarketing'))); ?></textarea>
	
	
	<p><input type="submit" value="<?php echo esc_attr(__( 'Submit', 'google_tags' ));?>" class="button button-primary button-large"></p>
	
</form>
</div>
<?php 	
}

//データの保存
add_action( 'admin_init', 'google_tags_admin_init' );

function google_tags_admin_init() {

	$e = new WP_Error(); 
	
	if(isset($_POST['google-tags']) && $_POST['google-tags']) {
				if (check_admin_referer( 'google-tabs-nonce-key', 'google-tags' ) ){
			
			if( isset($_POST['google-webmaster-tool']) && $_POST['google-webmaster-tool']) {
				update_option( 'google-webmaster-tool', trim( $_POST['google-webmaster-tool'] ) ); 	
				//アラート
				$e->add( 'update', 'Post the Webmaster tag.', 'my_google_tags' );
				set_transient( 'my_google_tags_notice', $e->get_error_messages(), 5 ); 
		
	
			} else {
				
				update_option( 'google-webmaster-tool', ''); 	
				
			}
			
			if( isset($_POST['google-analytics']) && $_POST['google-analytics']) {
				update_option( 'google-analytics', trim( $_POST['google-analytics'] ) ); 
				//アラート
				$e->add( 'update', 'Post the Analytics tag.', 'my_google_tags' );
				set_transient( 'my_google_tags_notice', $e->get_error_messages(), 5 ); 
	
			} else {
				
				update_option( 'google-analytics', '' ); 	
			}
			
			if( isset($_POST['google-remarketing']) && $_POST['google-remarketing']) {
				update_option( 'google-remarketing', trim( $_POST['google-remarketing'] ) ); 
				//アラート
				$e->add( 'update', 'Post the Remarketing tag.', 'my_google_tags' );
				set_transient( 'my_google_tags_notice', $e->get_error_messages(), 5 ); 
	
			} else {
				
				update_option( 'google-remarketing', '' ); 	
			}
			
			
			wp_safe_redirect( menu_page_url( 'google-tags', false )); 
			
			
			
			}
	
	}
		
}


//データの吐き出し
add_action('wp_head','google_webmaster_tool_head', 10);

function google_webmaster_tool_head() {
	
		$webmaster1 = '<!-- Google WebMaster by Tags Manager Plugin -->'; 
	if ( $webmaster = stripslashes(get_option('google-webmaster-tool')) ) {
		echo $webmaster1."\n"; 
		echo $webmaster. "\n"; 
	}


}

add_action('wp_head','google_analytics_head', 20);

function google_analytics_head() {
		
		$analytics1 = '<!-- Google Analytics by Tags Manager Plugin -->'; 
	if ( $analytics = stripslashes(get_option('google-analytics')) ) {
		echo $analytics1."\n"; 
		echo $analytics."\n"; 
	}

}

add_action('wp_footer','google_remarketing_footer', 80);

function google_remarketing_footer() {
	
		$remarketing1 = '<!-- Google Remarketing by Tags Manager Plugin -->'; 
	if ( $remarketing = stripslashes(get_option('google-remarketing')) ) {
		echo $remarketing1."\n"; 
		echo $remarketing."\n"; 	
	}

}


//エラー処理
add_action('admin_notices', 'google_tags_notices');

function google_tags_notices() {

?>
	<?php if ( $messages = get_transient( 'my_google_tags_notice' ) ): ?>
	<div class="updated">
		<ul>
			<?php foreach( $messages as $message ): ?>
				<li><?php echo esc_html( $message ); ?></li>
			<?php endforeach; ?>

		
		</ul>
	</div>
	<?php endif; ?>
	
<?php	
}








