<?php
if( !defined( 'ABSPATH')){ exit(); }

/****************************** список ************************************************/

add_action('pn_adminpage_title_pn_wmess', 'pn_adminpage_title_pn_wmess');
function pn_adminpage_title_pn_wmess(){
	_e('Warning messages','pn');
}

add_action('pn_adminpage_content_pn_wmess','def_pn_adminpage_content_pn_wmess');
function def_pn_adminpage_content_pn_wmess(){

	if(class_exists('trev_wmess_List_Table')){
		$Table = new trev_wmess_List_Table();
		$Table->prepare_items();
 
		pn_admin_searchbox(array(), 'reply');
 
		$options = array(
			'1' => __('published','pn'),
			'2' => __('moderating','pn'),
		);
		pn_admin_submenu('mod', $options, 'reply'); 	
	?>	

	<form method="post" action="<?php pn_the_link_post(); ?>">
		<?php $Table->display() ?>
	</form>
<?php 
	} else {
		echo 'Class not found';
	}
}


add_action('premium_action_pn_wmess','def_premium_action_pn_wmess');
function def_premium_action_pn_wmess(){
global $wpdb;	

	only_post();
	pn_only_caps(array('administrator','pn_wmess'));
	
	$reply = '';
	$action = get_admin_action();
	if(isset($_POST['id']) and is_array($_POST['id'])){

		if($action == 'approve'){	
			foreach($_POST['id'] as $id){
				$id = intval($id);	
				$item = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."warning_mess WHERE id='$id' AND status != '1'");
				if(isset($item->id)){
					do_action('pn_wmess_approve_before', $id, $item);
					$result = $wpdb->query("UPDATE ".$wpdb->prefix."warning_mess SET status = '1' WHERE id = '$id'");
					do_action('pn_wmess_approve', $id, $item);
					if($result){
						do_action('pn_wmess_approve_after', $id, $item);
					}
				}		
			}	
			$reply = '&reply=true';	
		}

		if($action == 'unapprove'){	
			foreach($_POST['id'] as $id){
				$id = intval($id);	
				$item = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."warning_mess WHERE id='$id' AND status != '0'");
				if(isset($item->id)){	
					do_action('pn_wmess_unapprove_before', $id, $item);
					$result = $wpdb->query("UPDATE ".$wpdb->prefix."warning_mess SET status = '0' WHERE id = '$id'");
					do_action('pn_wmess_unapprove', $id, $item);
					if($result){
						do_action('pn_wmess_unapprove_after', $id, $item);
					}					
				}
			}	
			$reply = '&reply=true';	
		}				
			
		if($action == 'delete'){	
			foreach($_POST['id'] as $id){
				$id = intval($id);
				$item = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."warning_mess WHERE id='$id'");
				if(isset($item->id)){		
					do_action('pn_wmess_delete_before', $id, $item);
					$result = $wpdb->query("DELETE FROM ".$wpdb->prefix."warning_mess WHERE id = '$id'");
					do_action('pn_wmess_delete', $id, $item);
					if($result){
						do_action('pn_wmess_delete_after', $id, $item);
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

class trev_wmess_List_Table extends WP_List_Table {

    function __construct(){
        global $status, $page;
                
        parent::__construct( array(
            'singular'  => 'id',      
			'ajax' => false,  
        ) );
        
    }
	
    function column_default($item, $column_name){
		if($column_name == 'text'){
			return pn_strip_text(ctv_ml($item->text));
		} elseif($column_name == 'class'){
			return pn_strip_input($item->theclass);
		} elseif($column_name == 'status'){
		    if($item->status == '0'){ 
			    return '<span class="bred">'. __('moderating','pn') .'</span>'; 
			} else { 
			    return '<span class="bgreen">'. __('published','pn') .'</span>'; 
			}	
		}
		return apply_filters('wmess_manage_ap_col', '', $column_name,$item);
    }	
	
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'], 
            $item->id                
        );
    }	

    function column_title($item){
        $actions = array(
            'edit'      => '<a href="'. admin_url('admin.php?page=pn_add_wmess&item_id='. $item->id) .'">'. __('Edit','pn') .'</a>',
        );		
		$primary = apply_filters('wmess_manage_ap_primary', get_mytime($item->datestart,'d.m.Y H:i').'-'.get_mytime($item->dateend,'d.m.Y H:i'), $item);
		$actions = apply_filters('wmess_manage_ap_actions', $actions, $item);		
        return sprintf('%1$s %2$s',
            $primary,
            $this->row_actions($actions)
        );
    }	
	
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',          
			'title'     => __('Date','pn'),
			'text'    => __('Text','pn'),
			'class'    => __('CSS class','pn'),
            'status'  => __('Status','pn'),
        );
		$columns = apply_filters('wmess_manage_ap_columns', $columns);
        return $columns;
    }

	function get_primary_column_name() {
		return 'title';
	}

	function single_row( $item ) {
		$class = '';
		if($item->status == 1){
			$class = 'active';
		}
		echo '<tr class="pn_tr '. $class .'">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}		

    function get_bulk_actions() {
        $actions = array(
			'approve'    => __('Approve','pn'),
			'unapprove'    => __('Decline','pn'),
            'delete'    => __('Delete','pn'),
        );
        return $actions;
    }
    
    function get_sortable_columns() {
        $sortable_columns = array( 
            'title'     => array('title',false),
        );
        return $sortable_columns;
    }	
	
    function prepare_items() {
        global $wpdb; 
		
        $per_page = $this->get_items_per_page('trev_wmess_per_page', 20);
        $current_page = $this->get_pagenum();
        
        $this->_column_headers = $this->get_column_info();

		$offset = ($current_page-1)*$per_page;
		
		$oby = is_param_get('orderby');
		if($oby == 'title'){
		    $orderby = 'datestart';
		} else {
		    $orderby = 'id';
		}
		$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';		
		if($order != 'asc'){ $order = 'desc'; }				
		
		$where = '';

		$mod = intval(is_param_get('mod'));
		if($mod == 1){
			$where = " AND status = '1'";
		} elseif($mod == 2){
			$where = " AND status = '0'";
		}	
		
		$where = pn_admin_search_where($where);
		
		$total_items = $wpdb->query("SELECT id FROM ". $wpdb->prefix ."warning_mess WHERE id > 0 $where");
		$data = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."warning_mess WHERE id > 0 $where ORDER BY $orderby $order LIMIT $offset , $per_page");  		

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
            <a href="<?php echo admin_url('admin.php?page=pn_add_wmess');?>" class="button"><?php _e('Add new','pn'); ?></a>
		</div>
		<?php
	}	  
}


add_action('premium_screen_pn_wmess','my_premium_screen_pn_wmess');
function my_premium_screen_pn_wmess() {
    $args = array(
        'label' => __('Display','pn'),
        'default' => 20,
        'option' => 'trev_wmess_per_page'
    );
    add_screen_option('per_page', $args );
	if(class_exists('trev_wmess_List_Table')){
		new trev_wmess_List_Table;
	}
}