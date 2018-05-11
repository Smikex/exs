<?php
/*
title: [en_US:]Payeer (withdraw to PS)[:en_US][ru_RU:]Payeer (вывод на ПС)[:ru_RU]
description: [en_US:]Payeer (withdraw to PS) automatic payouts[:en_US][ru_RU:]авто выплаты Payeer (вывод на платежные системы, карты и т.п.)[:ru_RU]
version: 1.3
*/

if(!class_exists('paymerchant_payeertops')){
	class paymerchant_payeertops extends AutoPayut_Premiumbox{
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
			add_action('myaction_merchant_ap_'.$this->name.'_cron' . get_hash_result_url($this->name, 'ap'), array($this,'myaction_merchant_cron'));
		}	
		
		function get_paymerchant_admin_options($options, $data){
			
			if(isset($options['bottom_title'])){
				unset($options['bottom_title']);
			}			
			if(isset($options['note'])){
				unset($options['note']);
			}			
			if(isset($options['checkpay'])){
				unset($options['checkpay']);
			}
			
			$statused = apply_filters('bid_status_list',array());
			if(!is_array($statused)){ $statused = array(); }

			$error_status = trim(is_isset($data, 'error_status'));
			if(!$error_status){ $error_status = 'realpay'; }
			$options[] = array(
				'view' => 'select',
				'title' => __('API status error','pn'),
				'options' => $statused,
				'default' => $error_status,
				'name' => 'error_status',
				'work' => 'input',
			);
			
			try {
				$types = array();
				$types[0] = '--' . __('No','pn') . '--';
				$payeer = new AP_Payeer(is_deffin($this->m_data,'ACCOUNT_NUMBER'), is_deffin($this->m_data,'API_ID'), is_deffin($this->m_data,'API_KEY'));
				$res = array();
				if ($payeer->isAuth())
				{
					$res = $payeer->getPaySystems();
						if(isset($res['list']) and is_array($res['list'])){
						foreach($res['list'] as $res_id => $res_data){
							$types[$res_id] = is_isset($res_data,'name') . ' [' . $res_id . ']';
						}
					}
				}
				$options[] = array(
					'view' => 'select',
					'title' => __('Transaction type','pn'),
					'options' => $types,
					'default' => is_isset($data, 'payment_type'),
					'name' => 'payment_type',
					'work' => 'input',
				);		
				/* 				
				$options['help_payment_type'] = array(
					'view' => 'help',
					'title' => __('More info','pn'),
					'default' => print_r($res, true),
				); 
				*/				
			}
			catch (Exception $e)
			{
				$options[] = array(
					'view' => 'textfield',
					'title' => '',
					'default' => $e,
				);							
			}

			$options['bottom_title'] = array(
				'view' => 'h3',
				'title' => '',
				'submit' => __('Save','pn'),
				'colspan' => 2,
			);			
			
			$text = '
			<strong>CRON:</strong> <a href="'. get_merchant_link('ap_'. $this->name .'_cron' . get_hash_result_url($this->name, 'ap')) .'" target="_blank">'. get_merchant_link('ap_'. $this->name .'_cron' . get_hash_result_url($this->name, 'ap')) .'</a>
			';
			$options[] = array(
				'view' => 'textfield',
				'title' => '',
				'default' => $text,
			);			
			
			return $options;
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
					if (!$account) {
						$error[] = __('Client wallet type does not match with currency code','pn');
					}					
					
					$sum = is_my_money(is_paymerch_sum($this->name, $item, $paymerch_data));
					
					$payment_type = is_my_money(is_isset($paymerch_data, 'payment_type'));
					if($payment_type == 0){
						$error[] = __('Not selected payment type','pn');
					}
					
					if(count($error) == 0){

						$result = update_bids_meta($item->id, 'ap_status', 1);
						update_bids_meta($item->id, 'ap_status_date', current_time('timestamp'));
						
						if($result){				
							try {
								$payeer = new AP_Payeer(is_deffin($this->m_data,'ACCOUNT_NUMBER'), is_deffin($this->m_data,'API_ID'), is_deffin($this->m_data,'API_KEY'));
								if ($payeer->isAuth()){
									
									$arr = array();
									$arr['ps'] = $payment_type;
									$arr['curIn'] = $vtype;
									$arr['sumOut'] = $sum;
									$arr['curOut'] = $vtype;
									$arr['param_ACCOUNT_NUMBER'] = $account;
									$arTransfer = $payeer->output($arr);
									
									if (empty($arTransfer['errors']) and isset($arTransfer['historyId'])){
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
						the_merchant_bid_status('coldsuccess', $item_id, 'user', 1, $place, $params);					
						 
						if($place == 'admin'){
							pn_display_mess(__('Automatic payout is done','pn'),__('Automatic payout is done','pn'),'true');
						} 
					}
				
				}
			}			
		}

		function myaction_merchant_cron(){
		global $wpdb;
			
			$m_out = $this->name;
			
			$data = get_paymerch_data($this->name);
			$error_status = is_status_name(is_isset($data, 'error_status'));
			if(!$error_status){ $error_status = 'realpay'; }
			
			$items = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."bids WHERE status = 'coldsuccess' AND m_out='$m_out'");
			foreach($items as $item){
				$trans_id = trim($item->trans_out);
				if($trans_id){
					try {
						$payeer = new AP_Payeer(is_deffin($this->m_data,'ACCOUNT_NUMBER'), is_deffin($this->m_data,'API_ID'), is_deffin($this->m_data,'API_KEY'));
						if($payeer->isAuth()){
							$arTransfer = $payeer->getHistoryInfo($trans_id);
							if (empty($arTransfer['errors']) and isset($arTransfer['info'])){
								$check_status = trim(is_isset($arTransfer['info'],'status'));
								if($check_status == 'execute'){
									$params = array();
									the_merchant_bid_status('success', $item->id, 'system', 1, 'site', $params);														
								} elseif($check_status != 'process' and $check_status != 'wait'){
									send_paymerchant_error($item->id, __('Your payment is declined','pn'));
									
									update_bids_meta($item->id, 'ap_status', 0);
									update_bids_meta($item->id, 'ap_status_date', current_time('timestamp'));
									
									$arr = array(
										'status'=> $error_status,
										'editdate'=> current_time('mysql'),
									);									
									$wpdb->update($wpdb->prefix.'bids', $arr, array('id'=>$item->id));
								}	
							}
						}	
					}
					catch( Exception $e ) {
										
					}
				}
			}
			
			/*
			execute - выполнен (конечный статус)
			process - в процессе выполнения (изменится на execute, cancel или hold)
			cancel - отменен (конечный статус)
			wait - в ожидании (например в ожидании оплаты) (изменится на execute, cancel или hold)
			hold - приостановлен (изменится на execute, cancel)
			black_list - операция остановлена из-за попадание под фильтр блэк-листа (может измениться на execute, cancel или hold)
			*/			
			
			_e('Done','pn');
		}		
		
	}
}
new paymerchant_payeertops(__FILE__, 'Payeer to ps');