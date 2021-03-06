<?php
/*
title: [en_US:]Payeer[:en_US][ru_RU:]Payeer[:ru_RU]
description: [en_US:]Payeer automatic payouts[:en_US][ru_RU:]авто выплаты Payeer[:ru_RU]
version: 1.3
*/

if(!class_exists('paymerchant_payeer')){
	class paymerchant_payeer extends AutoPayut_Premiumbox{
		function __construct($file, $title)
		{
			$map = array(
				'AP_BUTTON', 'ACCOUNT_NUMBER', 'API_ID', 'API_KEY',
			);
			parent::__construct($file, $map, $title, 'AP_BUTTON');
			
			add_action('get_paymerchant_admin_options_'.$this->name, array($this, 'get_paymerchant_admin_options'), 10, 2);
			add_filter('paymerchants_settingtext_'.$this->name, array($this, 'paymerchants_settingtext'));
			add_filter('reserv_place_list',array($this,'reserv_place_list'));
			add_filter('update_valut_autoreserv', array($this,'update_valut_autoreserv'), 10, 3);
			add_filter('update_naps_reserv', array($this,'update_naps_reserv'), 10, 4);
			add_action('paymerchant_action_bid_'.$this->name, array($this,'paymerchant_action_bid'),99,3);
		}

		function get_paymerchant_admin_options($options, $data){
			
			if(isset($options['checkpay'])){
				unset($options['checkpay']);
			}			
			if(isset($options['resulturl'])){
				unset($options['resulturl']);
			}			
			$noptions = array();
			foreach($options as $key => $val){
				$noptions[$key] = $val;
				if($key == 'note'){
					$noptions[] = array(
						'view' => 'warning',
						'default' => sprintf(__('Use only latin symbols in payment notes. Maximum: %s characters.','pn'), 100),
					);						
				}
			}		
			
			return $noptions;
		}	
		
		function paymerchants_settingtext(){
			$text = '| <span class="bred">'. __('Config file is not set up','pn') .'</span>';
			if(
				is_deffin($this->m_data,'ACCOUNT_NUMBER') 
				and is_deffin($this->m_data,'API_ID') 
				and is_deffin($this->m_data,'API_KEY')				
			){
				$text = '';
			}
			
			return $text;
		}

		function reserv_place_list($list){
			
			$purses = array(
				$this->name.'_1' => 'EUR',
				$this->name.'_2' => 'RUB',
				$this->name.'_3' => 'USD',
			);
			
			foreach($purses as $k => $v){
				$v = trim($v);
				if($v){
					$list[$k] = 'Payeer '. $v .' ['. is_deffin($this->m_data,'ACCOUNT_NUMBER') .']';
				}
			}
			
			return $list;
		}

		function update_valut_autoreserv($ind, $key, $valut_id){
			
			if($ind == 0){
				if(strstr($key, $this->name.'_')){
				
					$purses = array(
						$this->name.'_1' => 'EUR',
						$this->name.'_2' => 'RUB',
						$this->name.'_3' => 'USD',
					);
					
					$purse = trim(is_isset($purses, $key));
					if($purse){
						
						try{
					
							$payeer = new AP_Payeer(is_deffin($this->m_data,'ACCOUNT_NUMBER'), is_deffin($this->m_data,'API_ID'), is_deffin($this->m_data,'API_KEY'));
							if ($payeer->isAuth())
							{
								$rezerv = '-1';
								
								$arBalance = $payeer->getBalance();
								$rezerv = trim((string)$arBalance['balance'][$purse]['BUDGET']);
								
								if($rezerv != '-1'){
									pm_update_vr($valut_id, $rezerv);
								}								
							} 
						
						}
						catch (Exception $e)
						{
							
						} 				
						
						return 1;
					}
				
				}
			}
			
			return $ind;
		}

		function update_naps_reserv($ind, $key, $naps_id, $naps){
			
			if($ind == 0){
				if(strstr($key, $this->name.'_')){
				
					$purses = array(
						$this->name.'_1' => 'EUR',
						$this->name.'_2' => 'RUB',
						$this->name.'_3' => 'USD',
					);
					
					$purse = trim(is_isset($purses, $key));
					if($purse){
						
						try{
					
							$payeer = new AP_Payeer(is_deffin($this->m_data,'ACCOUNT_NUMBER'), is_deffin($this->m_data,'API_ID'), is_deffin($this->m_data,'API_KEY'));
							if ($payeer->isAuth())
							{
								$rezerv = '-1';
								
								$arBalance = $payeer->getBalance();
								$rezerv = trim((string)$arBalance['balance'][$purse]['BUDGET']);
								
								if($rezerv != '-1'){
									pm_update_nr($naps_id, $rezerv);
								}								
							} 
						
						}
						catch (Exception $e)
						{
							
						} 				
						
						return 1;
					}
				
				}
			}
			
			return $ind;
		}		

		function paymerchant_action_bid($item, $place, $naps_data){
		global $wpdb;
			
			$item_id = is_isset($item,'id');
			if($item_id){

				$paymerch_data = get_paymerch_data($this->name);
			
				$au_filter = array(
					'error' => array(),
					'pay_error' => 0,
					'enable' => 1,
				);
				$au_filter = apply_filters('autopayment_filter', $au_filter, $this->name, $item, $place, $naps_data, $paymerch_data);			
			
				$error = (array)$au_filter['error'];
				$pay_error = intval($au_filter['pay_error']);
				$trans_id = 0;			
			
				if($au_filter['enable'] == 1){		
			
					$vtype = mb_strtoupper($item->vtype2);
					$vtype = str_replace(array('RUR'),'RUB',$vtype);
					
					$enable = array('USD','RUB','EUR');
					if(!in_array($vtype, $enable)){
						$error[] = __('Wrong currency code','pn'); 
					}						
						
					$account = $item->account2;
					$account = mb_strtoupper($account);
					if (!$account) {
						$error[] = __('Client wallet type does not match with currency code','pn');
					}							

					$trans_sum = is_my_money(is_paymerch_sum($this->name, $item, $paymerch_data), 2);
					$sum = 0;
					if($trans_sum > 0){
						$sum = $trans_sum / 0.9905;
					}		
					
					if(count($error) == 0){

						$result = update_bids_meta($item->id, 'ap_status', 1);
						update_bids_meta($item->id, 'ap_status_date', current_time('timestamp'));					
					
						if($result){
					
							$notice = get_text_paymerch($this->name, $item);
							if(!$notice){ $notice = sprintf(__('ID order %s','pn'), $item->id); }
							$notice = trim(pn_maxf($notice,100));
						
							try{
						
								$payeer = new AP_Payeer(is_deffin($this->m_data,'ACCOUNT_NUMBER'), is_deffin($this->m_data,'API_ID'), is_deffin($this->m_data,'API_KEY'));
								if ($payeer->isAuth()){
									
									$arTransfer = $payeer->transfer(array(
										'curIn' => $vtype,
										'sum' => $sum,
										'curOut' => $vtype,
										//'to' => 'richkeeper@gmail.com',
										//'to' => '+01112223344',
										'to' => $account,
										'comment' => $notice,
										//'anonim' => 'Y',
										//'protect' => 'Y',
										//'protectPeriod' => '3',
										//'protectCode' => '12345',
									));								
									
									if (empty($arTransfer['errors']) and isset($arTransfer['historyId'])) {
										$trans_id = $arTransfer['historyId'];
									} else {
										$error[] = __('Payout error','pn');
										$pay_error = 1;
									}								
								} else {
									$pay_error = 1;
									$error[] = 'Error interfaice';
								}
							}
							catch (Exception $e)
							{
								$error[] = $e;
								$pay_error = 1;
							} 
							
						} else {
							$error[] = 'Database error';
						}
									
					}
					
					if(count($error) > 0){
						
						if($pay_error == 1){
							update_bids_meta($item->id, 'ap_status', 0);
							update_bids_meta($item->id, 'ap_status_date', current_time('timestamp'));
						}	
						
						$error_text = join('<br />',$error);
						
						do_action('paymerchant_error', $this->name, $error, $item_id, $place);
						
						if($place == 'admin'){
							pn_display_mess(__('Error!','pn') . $error_text);
						} else {
							send_paymerchant_error($item_id, $error_text);
						}
						
					} else {
						
						$params = array(
							'soschet' => is_deffin($this->m_data,'ACCOUNT_NUMBER'),
							'trans_out' => $trans_id,
						);
						the_merchant_bid_status('success', $item_id, 'user', 1, $place, $params);						
						
						if($place == 'admin'){
							pn_display_mess(__('Automatic payout is done','pn'),__('Automatic payout is done','pn'),'true');
						}  
						
					}
				
				}
			}
		}				
		
	}
}

new paymerchant_payeer(__FILE__, 'Payeer');