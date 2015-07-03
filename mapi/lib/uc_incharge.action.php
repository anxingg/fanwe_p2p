<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_incharge
{
	public function index(){
		
		$root = array();
		
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		if ($user_id >0){
			require APP_ROOT_PATH.'app/Lib/uc_func.php';
			
			$root['user_login_status'] = 1;
			$root['response_code'] = 1;
					
			//$root['show_err'] = get_domain();
			//输出支付方式
			$payment_list = $GLOBALS['db']->getAll("select id,class_name,fee_amount,description,logo,fee_type from ".DB_PREFIX."payment where is_effect = 1 and online_pay = 2 order by sort desc");
			foreach($payment_list as $k=>$v){
				$payment_list[$k]['logo'] = get_domain().$v['logo'];
			}
			
			$root['payment_list'] = $payment_list;
			
			//判断是否有线下支付
			$below_payment = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where is_effect = 1 and class_name = 'Otherpay'");
			$b_pay = array();
			if($below_payment){
				$below_payment['config'] = unserialize($below_payment['config']);
				/*
				$count = count($payment_item['config']['pay_name']);
				for($kk=0;$kk<$count;$kk++){
					$pay = array();
					$pay['id'] = $payment_item['id'];
					$pay['id'] = $payment_item['id'];
					
					$html .= "<div class='clearfix'>";
					$html .= "<label class='f_l w140'><input type='radio' name='payment' value='".$payment_item['id']."' onclick='set_bank(\"".$kk."\")' />".
							$payment_item['config']['pay_name'][$kk]."</label>".
							"<div class='f_l' style='line-height:24px'>收款人：".$payment_item['config']['pay_account_name'][$kk]."&nbsp;&nbsp;&nbsp;&nbsp;" .
							"收款帐号：".$payment_item['config']['pay_account'][$kk]."&nbsp;&nbsp;&nbsp;&nbsp;开户行：".$payment_item['config']['pay_bank'][$kk]."</div>";
					$html .="</div><div class='blank'></div>";
				}
				*/
				
				$count = count($below_payment['config']['pay_name']);
				for($kk=0;$kk<$count;$kk++){
					$pay = array();
					$pay['pay_id'] = $below_payment['id'];
					$pay['bank_id'] = $kk;
					$pay['pay_name'] = $below_payment['config']['pay_name'][$kk];
					$pay['pay_account_name'] = $below_payment['config']['pay_account_name'][$kk];
					$pay['pay_account'] = $below_payment['config']['pay_account'][$kk];
					$pay['pay_bank'] = $below_payment['config']['pay_bank'][$kk];
					
					$b_pay[] = $pay;
					
				}
			}
			
			$root['below_payment'] = $b_pay;
			
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>
