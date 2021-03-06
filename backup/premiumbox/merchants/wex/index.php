<?php
/*
title: [en_US:]WEX[:en_US][ru_RU:]WEX[:ru_RU]
description: [en_US:]WEX merchant[:en_US][ru_RU:]мерчант WEX[:ru_RU]
version: 1.3
*/

if(!class_exists('merchant_wex')){
	class merchant_wex extends Merchant_Premiumbox{
	
		function __construct($file, $title)
		{
			$map = array(
				'WEX_KEY', 'WEX_SECRET', 
			);
			parent::__construct($file, $map, $title);
			
			add_filter('merchants_settingtext_'.$this->name, array($this, 'merchants_settingtext'));
			add_filter('merchant_pay_button_'.$this->name, array($this,'merchant_pay_button'),99,4);
			add_filter('summ_to_pay',array($this,'summ_to_pay'),10,4);
			add_filter('get_merchant_admin_options_'.$this->name,array($this, 'get_merchant_admin_options'),1,2);
			add_action('myaction_merchant_'. $this->name .'_add', array($this,'myaction_merchant_add'));
			add_action('myaction_merchant_'. $this->name .'_status', array($this,'myaction_merchant_status'));
		}	
		
		function merchants_settingtext(){
			$text = '| <span class="bred">'. __('Config file is not set up','pn') .'</span>';
			if(
				is_deffin($this->m_data,'WEX_KEY')  
				and is_deffin($this->m_data,'WEX_SECRET') 
			){
				$text = '';
			}
			
			return $text;
		}

		function get_merchant_admin_options($options, $data){
					
			if(isset($options['check_api'])){
				unset($options['check_api']);
			}				
			if(isset($options['note'])){
				unset($options['note']);
			}
			if(isset($options['type'])){
				unset($options['type']);
			}
			if(isset($options['help_type'])){
				unset($options['help_type']);
			}
			if(isset($options['enableip'])){
				unset($options['enableip']);
			}
			if(isset($options['resulturl'])){
				unset($options['resulturl']);
			}			
			
			return $options;
		}	

		function summ_to_pay($sum, $m_id ,$item, $naps){ 
			
			if($m_id and $m_id == $this->name){
				return $item->summ1_dc;
			}	
			
			return $sum;
		}		
		
		function merchant_pay_button($temp, $pay_sum, $item, $naps){

			$temp = '<a href="'. get_merchant_link($this->name.'_add') .'?hash='. is_bid_hash($item->hashed) .'" target="_blank" class="success_paybutton">'. __('Make a payment','pn') .'</a>';

			return $temp;
		}

  		function myaction_merchant_add(){
			global $wpdb;	

			$hashed = is_bid_hash(is_param_get('hash'));
			$err = is_param_get('err');
			
			if($hashed){
				$item = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."bids WHERE hashed='$hashed'");
				if(isset($item->id)){
					$item_id = $item->id;
					$status = $item->status;
					$naps_id = intval($item->naps_id);
					$naps = $wpdb->get_row("SELECT * FROM ". $wpdb->prefix ."naps WHERE id='$naps_id'");
					$m_id = apply_filters('get_merchant_id','' , $naps->m_in, $item);
					if($status=='new' and $m_id and $m_id == $this->name){
						$sum = is_my_money($item->summ1_dc);
						
						echo apply_filters('merchant_header', '', $item, $naps);
					?>
					
						<?php if($err == '-1'){ ?>
							<div class="error_div">
								<?php _e('You have not entered a coupon code','pn'); ?>
							</div>
						<?php } ?>
						<?php if($err == '-2'){ ?>
							<div class="error_div">
								<?php _e('Coupon is not valid','pn'); ?>
							</div>
						<?php } ?>
						<?php if($err == '-3'){ ?>
							<div class="error_div">
								<?php _e('Coupon amount does not match the required amount','pn'); ?>
							</div>
						<?php } ?>
						<?php if($err == '-4'){ ?>
							<div class="error_div">
								<?php _e('Coupon currency code does not match the required currency','pn'); ?>
							</div>
						<?php } ?>			
					
						<div class="zone center">
							
							<p><?php _e('In order to pay an ID order','pn'); ?> <b><?php echo $item_id; ?></b>,<br /> <?php _e('enter coupon code valued','pn'); ?> <b><?php echo $sum; ?> WEX <?php echo is_site_value($item->vtype1); ?></b>:</p>
							
							<form action="<?php echo get_merchant_link($this->name.'_status'); ?>" method="post">
								<input type="hidden" name="hash" value="<?php echo $hashed; ?>" />
								<p><input type="text" placeholder="<?php _e('Code','pn'); ?>" required name="code" value="" /></p>
								<p><input type="submit" formtarget="_top" value="<?php _e('Submit code','pn'); ?>" /></p>
							</form>				
							
						</div>
						
					<?php 
						echo apply_filters('merchant_footer', '', $item, $naps);
					} else {
						wp_redirect(get_bids_url($hashed));
						exit;
					}	 
				} else {
					pn_display_mess(__('Error!','pn'));
				}	
			} else {
				pn_display_mess(__('Error!','pn'));
			}			
	
		}  
		
		function myaction_merchant_status(){
		global $wpdb;	

			$hashed = is_bid_hash(is_param_post('hash'));
			$code = trim(is_param_post('code'));
			if($hashed){
				$m_data = get_merch_data($this->name);
				$item = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."bids WHERE hashed='$hashed'");
				if(isset($item->id)){
					$item_id = $item->id;
					$status = $item->status;
					$naps_id = intval($item->naps_id);
					$naps = $wpdb->get_row("SELECT * FROM ". $wpdb->prefix ."naps WHERE id='$naps_id'");
					$m_id = apply_filters('get_merchant_id','' , $naps->m_in, $item);
					if($status=='new' and $m_id and $m_id == $this->name){
						$sum = is_my_money($item->summ1_dc);
						$sum = apply_filters('merchant_bid_sum', $sum, $m_id);
						$currency = strtoupper(str_replace('RUB','RUR',$item->vtype1));
						$invalid_ctype = intval(is_isset($m_data, 'invalid_ctype'));
						$invalid_minsum = intval(is_isset($m_data, 'invalid_minsum'));
						$invalid_maxsum = intval(is_isset($m_data, 'invalid_maxsum'));
						$invalid_check = intval(is_isset($m_data, 'check'));												
						if($code){
							try{
								$res = new WEX(is_deffin($this->m_data,'WEX_KEY'),is_deffin($this->m_data,'WEX_SECRET'));
								$info = $res->redeem_voucher($code);
								if($info){
									$merch_sum = is_isset($info,'amount');
									$merch_currency = strtoupper(is_isset($info,'currency'));
									$merch_trans_id = trim(is_isset($info,'trans_id'));
									if($merch_sum >= $sum or $invalid_minsum == 1){
										if($merch_currency == $currency or $invalid_ctype == 1){
										
											$params = array(
												'pay_purse' => '',
												'sum' => $merch_sum,
												'bid_sum' => $sum,
												'naschet' => '',
												'trans_in' => $merch_trans_id,
												'currency' => $merch_currency,
												'bid_currency' => $currency,
												'invalid_ctype' => $invalid_ctype,
												'invalid_minsum' => $invalid_minsum,
												'invalid_maxsum' => $invalid_maxsum,
												'invalid_check' => $invalid_check,												
											);
											the_merchant_bid_status('realpay', $item_id, 'user', 0, '', $params);											
											 
											wp_redirect(get_bids_url($hashed));
											exit;					
											
										} else {
											$back = get_merchant_link($this->name.'_add') .'?hash='. $hashed .'&err=-4';
											wp_redirect($back);
											exit;
										}
									} else {
										$back = get_merchant_link($this->name.'_add') .'?hash='. $hashed .'&err=-3';
										wp_redirect($back);
										exit;					
									}
								} else {
									$back = get_merchant_link($this->name.'_add') .'?hash='. $hashed .'&err=-2';
									wp_redirect($back);
									exit;							
								}
							}
							catch (Exception $e)
							{
								$show_error = intval(is_isset($m_data, 'show_error'));
								if($show_error){
									die($e);
								}	
								$back = get_merchant_link($this->name.'_add') .'?hash='. $hashed .'&err=-2';
								wp_redirect($back);
								exit;						
							}					
						} else {
							$back = get_merchant_link($this->name.'_add') .'?hash='. $hashed .'&err=-1';
							wp_redirect($back);
							exit;				
						}
					} else {
						wp_redirect(get_bids_url($hashed));
						exit;
					}	 
				} else {
					pn_display_mess(__('Error!','pn'));
				}	
			} else {
				pn_display_mess(__('Error!','pn'));
			}	

		}	 	
		
	}
}

new merchant_wex(__FILE__, 'WEX');