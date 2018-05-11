<?php
if( !defined( 'ABSPATH')){ exit(); }

/****************************** список ************************************************/

add_action('pn_adminpage_title_pn_blacklist', 'pn_admin_title_pn_blacklist');
function pn_admin_title_pn_blacklist(){
	_e('Blacklist','pn');
}

add_action('pn_adminpage_content_pn_blacklist','def_pn_admin_content_pn_blacklist');
function def_pn_admin_content_pn_blacklist(){

	if(class_exists('trev_blacklist_List_Table')){
		$Table = new trev_blacklist_List_Table();
		$Table->prepare_items();
		
		$search = array();
		$search[] = array(
			'view' => 'input',
			'title' => '',
			'default' => pn_strip_input(is_param_get('item')),
			'name' => 'item',
		);
		$options = array(
			'0' => __('everywhere','pn'),
			'1' => __('account','pn'),
			'2' => __('e-mail','pn'),
			'3' => __('phone number','pn'),
			'4' => __('skype','pn'),
			'5' => __('ip','pn'),
		);
		$search[] = array(
			'view' => 'select',
			'title' => '',
			'options' => $options,
			'default' => intval(is_param_get('witem')),
			'name' => 'witem',
		);		
		pn_admin_searchbox($search, 'reply');		
?>
	<form method="post" action="<?php pn_the_link_post(); ?>">
		<?php $Table->display() ?>
	</form>
	
	<div class="standart_window js_techwindow" id="window_to_comment"> 
		<div class="standart_windowins">
			<div class="standart_window_close"></div>
			<div class="standart_window_title" id="comment_the_title"><?php _e('Comment','pn'); ?></div>			
			<div class="standart_windowcontent">
				<form action="<?php pn_the_link_post('add_blacklist_comment'); ?>" class="ajaxed_comment_form" method="post">
					<p><textarea id="comment_the_text" name="comment"></textarea></p>
					<p><input type="submit" name="submit" class="button-primary" value="<?php _e('Save','pn'); ?>" /></p>
					<input type="hidden" name="id" id="comment_the_id" value="" />	
				</form>
			</div>
		</div>
	</div>	
	
<script type="text/javascript">
jQuery(function($){	 
/* comment */
	$('.uthametka').click(function(){
		var id = $(this).attr('id').replace('uthame-','');
		$('#comment_the_id').val(id);
		$('#window_to_comment').show();
		
		var hei = Math.ceil(($(window).height() - $('#window_to_comment').height()) / 2);
		var left = Math.ceil(($(window).width() - $('#window_to_comment').width()) / 2);
		$('#window_to_comment').css({'top': hei, 'left': left});		
		
		$('#window_to_comment input[type=submit]').attr('disabled',true);
		$('#comment_the_text').val('');
		
		var param = 'id=' + id;
		
		$.ajax({
			type: "POST",
			url: "<?php pn_the_link_post('blacklist_comment_get');?>",
			dataType: 'json',
			data: param,
			error: function(res, res2, res3){
				<?php do_action('pn_js_error_response', 'ajax'); ?>
			},			
			success: function(res)
			{		
			 
				$('#window_to_comment input[type=submit]').attr('disabled',false);
				if(res['status'] == 'error'){
					<?php do_action('pn_js_alert_response'); ?>
				} else {
					$('#comment_the_text').val(res['comment']);						
				}					
			}
		});		
		
	});
	
	$('.standart_window_close').click(function(){
		$('.js_techwindow').hide();
	});
	
	$('.ajaxed_comment_form').ajaxForm({
	    dataType:  'json',
        beforeSubmit: function(a,f,o) {
		    $('#window_to_comment input[type=submit]').attr('disabled',true);
        },
		error: function(res, res2, res3) {
			<?php do_action('pn_js_error_response', 'form'); ?>
		},		
        success: function(res) {
			$('#window_to_comment input[type=submit]').attr('disabled',false);
			if(res['status'] == 'error'){
				<?php do_action('pn_js_alert_response'); ?>
			} else {
				var id = $('#comment_the_id').val();
				var comf = $('#uthame-'+id);
				
				if(res['comment'] == 'true'){
					comf.addClass('active');
				} else {
					comf.removeClass('active');
				}
				$('.standart_shadow, #window_to_comment').hide();
			}
        }
    });	
/* end comment */

});
</script>	
<?php 
	} else {
		echo 'Class not found';
	}
}

/* comments */
add_action('premium_action_blacklist_comment_get', 'pn_premium_action_blacklist_comment_get');
function pn_premium_action_blacklist_comment_get(){
global $wpdb;
	only_post();

	$log = array();
	$log['status'] = '';
	$log['response'] = '';
	$log['status_code'] = 0; 
	$log['status_text'] = __('Error','pn');	

	if(current_user_can('administrator') or current_user_can('pn_blacklist')){
		$id = intval(is_param_post('id'));
		$item = $wpdb->get_row("SELECT * FROM ". $wpdb->prefix ."blacklist WHERE id='$id'");
		$comment = pn_strip_text(is_isset($item,'comment_text'));
		$log['status'] = 'success';
		$log['comment'] = $comment;
	} else {
		$log['status'] = 'error';
		$log['status_code'] = 1; 
		$log['status_text'] = __('Authorisation Error','pn');
	}	
	
	echo json_encode($log);
	exit;
}

add_action('premium_action_add_blacklist_comment', 'pn_premium_action_add_blacklist_comment');
function pn_premium_action_add_blacklist_comment(){
global $wpdb;
	only_post();

	$log = array();
	$log['status'] = '';
	$log['response'] = '';
	$log['status_code'] = 0; 
	$log['status_text'] = __('Error','pn');
	
	if(current_user_can('administrator') or current_user_can('pn_blacklist')){
		$id = intval(is_param_post('id'));
		$text = pn_strip_input(is_param_post('comment'));
		$wpdb->update($wpdb->prefix.'blacklist', array('comment_text'=>$text), array('id'=>$id));
		$log['status'] = 'success';
		if($text){
			$log['comment'] = 'true';
		} else {
			$log['comment'] = 'false';
		}
	} else {
		$log['status'] = 'error';
		$log['status_code'] = 1; 
		$log['status_text'] = __('Authorisation Error','pn');
	}	
	
	echo json_encode($log);	
	exit;
}
/* end comments */

add_action('premium_action_pn_blacklist','def_premium_action_pn_blacklist');
function def_premium_action_pn_blacklist(){
global $wpdb;	

	only_post();
	pn_only_caps(array('administrator','pn_blacklist'));

	$reply = '';
	$action = get_admin_action();
	if(isset($_POST['id']) and is_array($_POST['id'])){			
			
		if($action == 'delete'){	
			foreach($_POST['id'] as $id){
				$id = intval($id);
				$item = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."blacklist WHERE id='$id'");
				if(isset($item->id)){
					do_action('pn_blacklist_delete_before', $id, $item);
					$result = $wpdb->query("DELETE FROM ".$wpdb->prefix."blacklist WHERE id = '$id'");
					do_action('pn_blacklist_delete', $id, $item);
					if($result){
						do_action('pn_blacklist_delete_after', $id, $item);
					}
				}
			}
					
			$reply = '&reply=true';	
		}

	} 
			
	$url = is_param_post('_wp_http_referer') . $reply;
	$paged = intval(is_param_post('paged'));
	if($paged > 1){ $url .= '&paged='.$paged; }		
	wp_redirect($url);
	exit;			
} 

class trev_blacklist_List_Table extends WP_List_Table {

    function __construct(){
        global $status, $page;
                
        parent::__construct( array(
            'singular'  => 'id',      
			'ajax' => false,  
        ) );
        
    }
	
    function column_default($item, $column_name){
        
		if($column_name == 'ctype'){
			$arr = array('0'=>__('invoice','pn'),'1'=>__('e-mail','pn'),'2'=>__('phone number','pn'),'3'=>__('skype','pn'),'4'=>__('ip','pn'));
			return is_isset($arr,$item->meta_key);	
		} elseif($column_name == 'comment') {
			$id = $item->id;
			$comment_text = trim($item->comment_text);
			if($comment_text){ $cl='active'; } else { $cl=''; }		
			return '<div class="uthametka question_div '. $cl .'" id="uthame-'. $id .'"></div>';		
		} 
		
		return apply_filters('blacklist_manage_ap_col', '', $column_name,$item);
		
    }	
	
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'], 
            $item->id                
        );
    }	

    function column_cvalue($item){

        $actions = array(
            'edit'      => '<a href="'. admin_url('admin.php?page=pn_add_blacklist&item_id='. $item->id) .'">'. __('Edit','pn') .'</a>',
        );
  		$primary = apply_filters('blacklist_manage_ap_primary', pn_strip_input($item->meta_value), $item);
		$actions = apply_filters('blacklist_manage_ap_actions', $actions, $item);         
        return sprintf('%1$s %2$s',
            $primary,
            $this->row_actions($actions)
        );
		
    }	
	
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',          
			'cvalue'     => __('Value','pn'),
			'ctype'    => __('Type','pn'),
			'comment'     => __('Comment','pn'),
        );
  		$columns = apply_filters('blacklist_manage_ap_columns', $columns);
        return $columns;
    }	
	

    function get_bulk_actions() {
        $actions = array(
            'delete'    => __('Delete','pn'),
        );
        return $actions;
    }
    
    function prepare_items() {
        global $wpdb; 
		
        $per_page = $this->get_items_per_page('trev_blacklist_per_page', 20);
        $current_page = $this->get_pagenum();
        
        $this->_column_headers = $this->get_column_info();

		$offset = ($current_page-1)*$per_page;
		$where = '';

		$item = pn_sfilter(pn_strip_input(is_param_get('item')));
        if($item){ 
            $where .= " AND meta_value LIKE '%$item%'";
		}		
		
		$witem = intval(trim(is_param_get('witem')));
        if($witem){ 
			$witem = intval($witem);
			if($witem > 0){
				$witem = $witem - 1;
				$where .= " AND meta_key = '$witem'";
			}
		}		
		
		$where = pn_admin_search_where($where);
		$total_items = $wpdb->query("SELECT id FROM ". $wpdb->prefix ."blacklist WHERE id > 0 $where");
		$data = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."blacklist WHERE id > 0 $where ORDER BY id DESC LIMIT $offset , $per_page");  		

        $current_page = $this->get_pagenum();
        $this->items = $data;
		
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  
            'per_page'    => $per_page,                     
            'total_pages' => ceil($total_items/$per_page)  
        ));
    }
	
	function extra_tablenav( $which ) {
    ?>
		<div class="alignleft actions">
            <a href="<?php echo admin_url('admin.php?page=pn_add_blacklist');?>" class="button"><?php _e('Add new','pn'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=pn_add_blacklist_many');?>" class="button"><?php _e('Add list','pn'); ?></a>
		</div>
		<?php
	}	  
	
}

add_action('premium_screen_pn_blacklist','my_myscreen_pn_blacklist');
function my_myscreen_pn_blacklist() {
    $args = array(
        'label' => __('Display','pn'),
        'default' => 20,
        'option' => 'trev_blacklist_per_page'
    );
    add_screen_option('per_page', $args );
	if(class_exists('trev_blacklist_List_Table')){
		new trev_blacklist_List_Table;
	}
}