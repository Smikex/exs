<?php
function ficus_scripts() {
     wp_enqueue_style( 'css', get_site_url() . '/wp-content/themes/exchangerChild/css/bootstrap.min.css', array(), '' );
    //  wp_enqueue_style( 'css1', get_site_url() . '/wp-content/themes/exchangerChild/fonts.css', array(), '' );
     wp_enqueue_style( 'style', get_stylesheet_uri() );

//     wp_register_script( 'min', get_template_directory_uri() . '/assets/js/vendor.min.js' );
//    wp_enqueue_script( 'min' );
wp_register_script( 'bootstrap', get_site_url() . '/wp-content/themes/exchangerChild/js/bootstrap.min.js' );
wp_enqueue_script( 'bootstrap' );
wp_register_script( 'base', get_site_url() . '/wp-content/themes/exchangerChild/js/main.js' );
wp_enqueue_script( 'base' );
}
add_action('wp_enqueue_scripts','ficus_scripts');




// Форма основные данные


if(!function_exists('prepare_form_fileds_my')){
	function prepare_form_fileds_my($items, $filter, $prefix){
		$ui = wp_get_current_user();
		$html = '';
		if(is_array($items)){
			foreach($items as $name => $data){
				$type = trim(is_isset($data, 'type'));
				$name = trim(is_isset($data, 'name'));
				$title = trim(is_isset($data, 'title'));
				$req = intval(is_isset($data, 'req'));
				$not_auto = intval(is_isset($data, 'not_auto'));
				$disable = intval(is_isset($data, 'disable'));
				$placeholder = trim(is_isset($data, 'placeholder'));
				$value = is_isset($data, 'value');
				$classes = explode(',',is_isset($data, 'classes'));
				$tooltip = pn_strip_input(ctv_ml(is_isset($data, 'tooltip')));	
				
				$req_html = '';
				if($req){
					$req_html = ' <span class="req">*</span>';
				}
				$not_auto_html = '';
				if($not_auto){
					$not_auto_html = 'autocomplete="off"';
				}
				$disabled = '';
				if($disable){
					$disabled = 'disabled="disabled"';
				}			
				
				$class = join(' ',$classes);
				
				$tooltip_div = '';
				$tooltip_span = '';
				$tooltip_class = '';
				if($tooltip){
					$tooltip_span = '<span class="field_tooltip_label"></span>';
					$tooltip_class = 'has_tooltip';
					$tooltip_div = '<div class="field_tooltip_div"><div class="field_tooltip_abs"></div><div class="field_tooltip">'. $tooltip .'</div></div>';
				}
				
				    $line = '';
					if($title){
						$line .= '<div class="input_wrap"><label>'. $title .''. $req_html .':'. $tooltip_span .'</label>';
					}
					$line .= '';
				
				if($type == 'text'){
					$line .= '
					<textarea id="'. $prefix .'_id-'. $name .'" class="'. $prefix .'_textarea '. $class .'" '. $disabled .' placeholder="'. $placeholder .'" '. $not_auto_html .' name="'. $name .'">'. $value .'</textarea>							
					';	
				} elseif($type == 'input'){
					$line .= '
					<input type="text" '. $disabled .' placeholder="'. $placeholder .'" '. $not_auto_html .' name="'. $name .'" value="'. $value .'" /></div>';
				} elseif($type == 'password'){
					$line .= '
					<input type="password" id="'. $prefix .'_id-'. $name .'" class="'. $prefix .'_input '. $class .'" '. $disabled .' placeholder="'. $placeholder .'" '. $not_auto_html .' name="'. $name .'" value="'. $value .'" />						
					';				
				} elseif($type == 'select'){
					$options = (array)is_isset($data, 'options');
					$line .= '
					<select name="'. $name .'" id="'. $prefix .'_id-'. $name .'" class="'. $prefix .'_select '. $class .'" '. $disabled .' autocomlete="off">';
						foreach($options as $key => $title){
							$line .= '<option value="'. $key .'" '. selected($value, $key, false) .'>'. $title .'</option>';
						}
					$line .= '		
					</select>												
					';
				}
				
				$line .= '
					
					
				
					
					'. $tooltip_div .'
				
				';
			
				$line = apply_filters('form_field_line', $line, $filter, $data, $ui);
				$html .= apply_filters($filter, $line, $data, $ui);
			}
		}	
		return $html;
	}
}



// шорткод account_page


function account_page_shortcode2($atts, $content) {
    global $wpdb;
    
        $temp = '';
        
        $temp .= apply_filters('before_account_page','');
                
        $ui = wp_get_current_user();
        $user_id = intval($ui->ID);		
                
        if($user_id){
                
            $items = get_account_form_filelds();
            $html = prepare_form_fileds_my($items, 'account_form_line', 'acf');		
        
            $array = array(
                '[form]' => '<form method="post" class="ajax_post_form  " action="'. get_ajax_link('accountform') .'"><div class="input_wrapItem">',
                '[/form]' => '</form>',
                '[result]' => '<div class="resultgo"></div>',
                '[html]' => $html,
                '[submit]' => '</div><input type="submit" formtarget="_top" name="submit" class="yellow-btn" value="'. __('Save', 'pn') .'" /></div></div>',
            );	
        
            $temp_form = '
          
	<div class="container-fluid sec_gray">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="sec_white">
						<h1 class="title-h1 text-center">
                        '. __('Personal data','pn') .'</h1>

                        <ul class="nav nav-tabs lk-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#room" aria-controls="room" role="tab" data-toggle="tab">
                                <img src="http://exs.one/wp-content/themes/exchangerChild/images/lk-profile.png" alt="lk-profile">
                                <span>Личный кабинет</span>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#operations" aria-controls="operations" role="tab" data-toggle="tab">
                                <img src="http://exs.one/wp-content/themes/exchangerChild/images/lk-dollar.png" alt="lk-dollar">
                                <span>Мои опреации</span>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#card" aria-controls="card" role="tab" data-toggle="tab">
                                <img src="http://exs.one/wp-content/themes/exchangerChild/images/lk-card.png" alt="lk-card">
                                <span>Верификация карты</span>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
                                <img src="http://exs.one/wp-content/themes/exchangerChild/images/lk-settings.png" alt="lk-settings">
                                <span>Настройки безопастности</span>
                            </a>
                        </li>
                      </ul>
            
                <!-- Tab panes -->
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane lk-wrap active" id="room">
                      <h4 class="title-h1">Основные данные:</h4>
                              [form]
                              [html]
                              [submit]
                              [result]
                              [/form]     
                      <hr>
                      <h4 class="title-h1">Уведомления о статусе заявки:</h4>
                      <p>Для вашего удобства мы добавили функцию, с помощью которой мы сможем отправлять Push уведомления на Ваш рабочий стол </p>
                      <a href="#" class="yellow-btn subscribe-btn">
                          <img src="http://exs.one/wp-content/themes/exchangerChild/images/bell.png" alt="bell">
                          <span>подписаться</span>
                      </a>
                  </div> 
                  </div> 
                  </div> 
                  </div> 
                  </div> 
                  </div> 

            ';
        
            $temp_form = apply_filters('account_form_temp',$temp_form);
            $temp .= get_replace_arrays($array, $temp_form);		    
        } else {
            $temp .= '<div class="resultfalse">'. __('Error! You must authorize','pn') .'</div>';
        }
        
        $after = apply_filters('after_account_page','');
        $temp .= $after;	
        
        return $temp;
    }
    add_shortcode('account_page', 'account_page_shortcode2');
    


// tarif


function tarifs_shortcode2($atts, $content) {
    global $wpdb, $post;
            
        $temp = '';
        
        $ui = wp_get_current_user();
        $user_id = intval($ui->ID);
            
        $temp .= apply_filters('before_tarifs_page','');		
            
        $show_data = pn_exchanges_output('tar'); 
        if($show_data['text']){
            $temp .= '<div class="resultfalse"><div class="resultclose"></div>'. $show_data['text'] .'</div>';
        }			
        
        if($show_data['mode'] == 1){
            $v = get_valuts_data();
            $where = get_naps_where('tar');
            $napobmens = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."naps WHERE $where ORDER BY site_order1 ASC");
            $naps = $naps2 = array();
            foreach($napobmens as $napob){
                $output = apply_filters('get_naps_output', 1, $napob, 'tar');
                if($output){
                    $naps[$napob->valut_id1] = $napob;
                    $naps2[$napob->valut_id1][] = $napob;
                }
            }		

                $temp .='
                <div class="container-fluid sec_gray">
                <div class="container">
                    <h1 class="title-h1 top-title">Тарифы</h1>
                    <div class="row">
                    <div class="col-md-8">
                ';	
            
                foreach($naps as $data){
                    $valut_id = $data->valut_id1;
                    if(isset($v[$valut_id])){	
                        $vd = $v[$valut_id];
                        
                        $tarif_title = get_valut_title($vd);
                        $tarif_logo = get_valut_logo($vd);
                        
                        $temp .= '<div class="sec_white rates">';
                        
                            $one_tarifs_title = '
                            <h4 class="title-h1">'. $tarif_title .'</h4>';
                            $temp .= apply_filters('one_tarifs_title', $one_tarifs_title, $tarif_title, $tarif_logo, $vd);
    
                            $before_one_tarifs_block = '
                            <table>
                            <thead>
                                <tr>
                                    <th >'. __('You send','pn') .'</th>
                                    <th ></th>
                                    <th >'. __('You receive','pn') .'</th>
                                    <th >'. __('Reserve','pn') .'</th>
                                </tr>			
                                </thead>				
                            ';
                            $temp .= apply_filters('before_one_tarifs_block',$before_one_tarifs_block, $tarif_title, $vd);
    
                            if(is_array($naps2[$valut_id])){
                                $tarifs = $naps2[$valut_id];
                                foreach($tarifs as $tar){
                                    
                                    $valsid1 = $tar->valut_id1;
                                    $valsid2 = $tar->valut_id2;
                                    
                                    if(isset($v[$valsid1]) and isset($v[$valsid2])){
                                    
                                        $vd1 = $v[$valsid1];
                                        $vd2 = $v[$valsid2];
                                    
                                        $curs1 = is_out_sum(get_course1($tar->curs1, $vd1->lead_num, $vd1->valut_decimal, 'tarifs'), $vd1->valut_decimal, 'course');
                                        $curs2 = is_out_sum(get_course2($vd1->lead_num, $tar->curs1, $tar->curs2, $vd2->valut_decimal, 'tarifs'), $vd2->valut_decimal, 'course');
                                    
                                        $reserv = is_out_sum(get_naps_reserv($vd2->valut_reserv , $vd2->valut_decimal, $tar), $vd2->valut_decimal, 'reserv');
                                    
                                        $one_tarifs_line = '
                                        <tr  name="'. get_exchange_link($tar->naps_name) .'">
                                            <td ><div><strong>'. is_site_value($vd1->vtype_title) .'  '. $curs1 .'</strong>'. get_valut_title($vd1) .'</div></td>
                                            <td ">
  
                                            </td>
                                            <td ><div class="arrow"><div class="arrow"><strong>'. $curs2 .'&nbsp;'. is_site_value($vd2->vtype_title) .'</strong>'. get_valut_title($vd2) .'</div></td>
	
                                            <td >
                                                '. $reserv .'
                                            </td>
                                        </tr>
                                      
                                        ';
                                
                                        $temp .= apply_filters('one_tarifs_line',$one_tarifs_line, $tar, $curs1, $curs2, $reserv, $vd1, $vd2);
                                
                                    }
                                }
                            }
                            $after_one_tarifs_block = '
                            </table>';
                            $temp .= apply_filters('after_one_tarifs_block',$after_one_tarifs_block, $tarif_title, $vd);
                        
                        $temp .= '
                        </div>

                        ';					
                        
                    }
                }		
        
            $temp .='
                </div>
            </div>
            </div>';	
        } 
        
        $temp .= apply_filters('after_tarifs_page','');
        
        return $temp;
    }
    add_shortcode('tarifs', 'tarifs_shortcode2');