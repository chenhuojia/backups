<?php

namespace Acme\MinsuBundle\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\MinsuBundle\Entity\DrawBalance;
use Acme\MinsuBundle\Entity\MyWallet;

use Acme\MinsuBundle\Common\CommonController;
class apiWalletController extends CommonController
{
	
    public function __construct(){
    
    }
	/**
	 * @Route("/apiMyWallet", name="api_my_wallet_");
	 */
	public function apiMyWalletlAction(){
		
		$conn =$this->getDoctrine()->getManager()->getConnection();
		$data = isset($_POST)?$_POST:'';
		$par =array('member_id');
		$parBool  =	$this->checkKeyForArr($par, $data);
		if($parBool>0 &&  $data!=""){
			$query = $conn->createQueryBuilder()
			                    ->select("m.available_balance")
								->from('msk_member', 'm')
								//->leftJoin('m','msk_my_wallet','mw','m.id=mw.member_id')
								->where('m.id = :id' )
								->setParameter('id',$data['member_id'])
							    //->orderBy('mw.add_time','DESC')
								->execute();
			$message['my_c'] = current($query->fetchAll());
			//收入支出记录
			$message['my_w'] = $conn->createQueryBuilder()
								->select('m.*')
								->from('msk_my_wallet', 'm')
								->where('m.member_id = :id' )
								->setParameter('id',$data['member_id'])
								->orderBy('m.add_time','DESC')
								->execute()
			                    ->fetchAll();
			$where="FROM_UNIXTIME(m.add_time, '%Y-%m-%d')  = FROM_UNIXTIME(unix_timestamp(), '%Y-%m-%d') ";
			//今日的收入
			$message['my_to_income'] = $conn->createQueryBuilder()
								->select('SUM(m.income) Total')
								->from('msk_my_wallet', 'm')
								->where('m.member_id = :id' )
								->andWhere($where)
								->andWhere("m.income<>0")
								->setParameter('id',$data['member_id'])
								->orderBy('m.add_time','DESC')
								->execute()
			                    ->fetchAll();
		}else{
			$message= $this->msg_error();
		}
		return new JsonResponse($message);
	}
	
	/**
	 * @Route("/apiInWithdraw", name="api_my_wallet_in_withdraw");
	 */
	public function apiInWithdrawAction(){
		$conn =$this->getDoctrine()->getManager()->getConnection();
		$data = isset($_POST)?$_POST:'';
		$par =array('member_id');
		$parBool  =	$this->checkKeyForArr($par, $data);
		if($parBool>0 &&  $data!=""){
			$query = $conn->createQueryBuilder()
							->select("m.available_balance,m.freeze_date,m.ali_pre_acc,m.true_name,m.paypwd")
							->from('msk_member', 'm')
							->where('m.id = :id' )
							->setParameter('id',$data['member_id'])
							->execute();
			$data_meb= current($query->fetchAll());
			if ($data_meb['paypwd']  == null ){
				$message['state'] = 40; //尚未设置密码
				$message['available_balance'] =$data_meb['available_balance'];
			}elseif($data_meb['freeze_date']>0){
				if($data_meb['freeze_date'] > time()){
					$message['state'] =30;  //账户已被冻结
				}else{
					$upb =$conn->createQueryBuilder ()
								->update ( 'msk_member', 'm' )
								->set ('m.freeze_date',0 )
								->where('m.id = :id' )
								->setParameter('id',$data['member_id'])
								->execute ();
					if($upb){
						$message['state'] = 20;
						$message['error'] = 0;
						$message['msg'] ="suc";
						$message['available_balance'] =$data_meb['available_balance'];
						$message['ali_pre_acc'] =$data_meb['ali_pre_acc'];
						$message['true_name'] =$data_meb['true_name'];
					}else{
						$message['state'] = 70;
						$message['error'] = 1;
						$message['msg'] ="acc eor";
					}
				
				}
			} else{
				$message['state'] = 20;  //账户正常
				$message['error'] = 0;
				$message['msg'] ="suc";
				$message['available_balance'] =$data_meb['available_balance'];
				$message['ali_pre_acc'] =$data_meb['ali_pre_acc'];
				$message['true_name'] =$data_meb['true_name'];
			}
		}else{
			$message= $this->msg_error();
		}
		return new JsonResponse($message);
		
	}
	
	
	/**
	 * @Route("/apiSetPaypwd", name="api_my_wallet_set_paypwd");
	 */
	public function apiSetPaypwdAction(){
		$conn =$this->getDoctrine()->getManager()->getConnection();
		$data = isset($_POST)?$_POST:'';
		$par =array('member_id','ppwd','oppwd');
		$parBool  =	$this->checkKeyForArr($par, $data);
		if($parBool>0 &&  $data!=""){
			if($data['oppwd']!=0){
				$post_pwd  =$data['oppwd'] ;
				$query_m = $conn->createQueryBuilder()
								->select('m.*')
								->from('msk_member', 'm')
								->where('m.id = :id' )
								->setParameter('id',$data['member_id'])
								->execute();
				$meb_data = current($query_m->fetchAll());
				$opaypwd  =$meb_data['paypwd'];
				$old_pwd =	$this->paypwd_decode($opaypwd);
				if($old_pwd  == $post_pwd){
					$ne_pwd  =$this->paypwd_encode($data['ppwd']);
					$up_ppwd =$conn->createQueryBuilder ()
									->update ( 'msk_member', 'm' )
									->set ('m.paypwd',"'$ne_pwd'" )
									->where('m.id = :id' )
									->setParameter('id',$data['member_id'])
									->execute ();
					if($up_ppwd){
						$message= $this->msg_succ();
					}else{
						$message['status'] = 0;
						$message['error'] = 1;
						$message['message'] = 'change error';
					}
				}else{
					$message['status'] = 0;
					$message['error'] = 1;
					$message['message'] = 'no match';
				}
			}else{
				$pwd =	$this->paypwd_encode($data['ppwd']);	
				$upb =	$conn->createQueryBuilder ()
							->update ( 'msk_member', 'm' )
							->set ('m.paypwd',"'$pwd'" )
							->where('m.id = :id' )
							->setParameter('id',$data['member_id'])
							->execute ();
				if($upb){
					$message= $this->msg_succ();
				}else{
					$message['status'] = 0;
					$message['error'] = 1;
					$message['message'] = 'setup error';
				}
			}
			
		}
		else{
			$message['status'] = 0;
			$message['error'] = 1;
			$message['message'] = 'Parameters Error!';
		
		}
		return new JsonResponse($message);
	}
	
	/**
	 * @Route("/apiDrawBalance", name="api_my_wallet_draw_balance");
	 */
	public function apiDrawBalanceAction(){
		$conn =$this->getDoctrine()->getManager()->getConnection();
		$manager = $this->getDoctrine()->getManager();
		$data = isset($_POST)?$_POST:'';
		$par =array('member_id','ppwd','c_name','balance','ali_pre_acc');
		$parBool  =	$this->checkKeyForArr($par, $data);
		if($parBool>0 &&  $data!=""){
			$query_m = $conn->createQueryBuilder()
							->select('m.*')
							->from('msk_member', 'm')
							->where('m.id = :id' )
							->setParameter('id',$data['member_id'])
							->execute();
			$meb_data = current($query_m->fetchAll());
			$opaypwd  =$meb_data['paypwd'];
			$post_paypwd  =$this->paypwd_encode($data['ppwd'])  ;
			if($post_paypwd  == $opaypwd){
				$draw  = new DrawBalance();
				$draw->setCName($data['c_name']);
				$draw->setAliPreAcc($data['ali_pre_acc']);
				$draw->setBalance($data['balance']);
				$draw->setMemberId($data['member_id']);
				$draw->setAddTime(time());
				$manager->persist($draw);
				$manager->flush();
				if($draw->getId()){
					$message= $this->msg_succ();
				}else{
					$message=$this->msg_error();
				}
			}else{
				$message['status'] = 0;
				$message['error'] = 1;
				$message['message'] = 'no match';
			}
			
		}else{
			$message['status'] = 0;
			$message['error'] = 1;
			$message['message'] = 'Parameters Error!';
		
		}
		return new JsonResponse($message);
	}
	
	/**
	 * @Route("/apiAdReWalD", name="api_my_wallet_rec_lie_balance");
	 */
	public function apiAdReWalDAction(){
		$manager = $this->getDoctrine()->getManager();
		$data = isset($_POST)?$_POST:'';
		$par =array('member_id','income','expend','title','dscp');
		$parBool  =	$this->checkKeyForArr($par, $data);
		if($parBool>0 &&  $data!=""){
			$wal =new MyWallet();
			$wal->setMemberId($data['member_id']);
			$wal->setIncome($data['income']);
			$wal->setExpend($data['expend']);
			$wal->setTitle($data['title']);
			$wal->setDscp($data['dscp']);
			$wal->setAddTime(time());
			$manager->persist($wal);
			$manager->flush();
			if($wal->getId()){
				$pr =$this->changeTotalBalance($data['member_id']);
				if($pr){
					$message= $this->msg_succ();
				}else{
					$message=$this->msg_error();
				}
			}else{
				$message=$this->msg_error();
			}
		}else{
			$message['status'] = 0;
			$message['error'] = 1;
			$message['message'] = 'Parameters Error!';
		}
		return new JsonResponse($message);
	}

}
