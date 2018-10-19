<?php

namespace App\Models;

use PDO;
use App\Models\UserModel;


/**
 * Example user model
 *
 * PHP version 7.0
 */
class AuditModel extends \Core\Model
{
    private $db;

    function __construct(){
        $this->db = static::connect();
    }
    /*
    *Function for fetching financial averages
    *
    */
    public function get_avg($id, $table){

    	$stmt = $this->db->prepare("SELECT AVG(bal_after) as avg FROM $table WHERE id = :id");
    	$stmt->bindValue(':id', $id);

    	if($stmt->execute()){
    		$avg = $stmt->fetch(PDO::FETCH_ASSOC);
    		return $avg['avg'];
    	}
    }

   /*
    *Function for fetching financial averages
    *
    */
    public function get_cur_finance_bal($id, $table){

    	$stmt = $this->db->prepare("SELECT bal_after FROM $table WHERE id = :id ORDER BY date_time DESC LIMIT 1");
    	$stmt->bindValue(':id', $id);

    	if($stmt->execute()){
    		$bal = $stmt->fetch(PDO::FETCH_ASSOC);
    		return $bal['bal_after'];
    	}
    }

    /*
    *Function adding new lease
    *
    */
    public function add_lease($id, $data){

    	try{
    		$stmt = $this->db->prepare("INSERT INTO leases (userid, class, cost, balance, least_pay, location, tax_charge, date_leased, status) VALUES (:userid, :class, :cost, :balance, :least_pay,  :location, :tax_charge, :date_leased, :status)");
	    	$stmt->bindValue(':userid', $id);
	    	$stmt->bindValue(':class', $data['housing_class']);
	    	$stmt->bindValue(':cost', $data['cost']);
	    	$stmt->bindValue(':balance', -$data['cost']);
	    	$stmt->bindValue(':least_pay', $data['least_pay']);
	    	$stmt->bindValue(':location', $data['location']);
	    	$stmt->bindValue(':tax_charge', $data['tax_charge']);
	    	$stmt->bindValue(':date_leased', date('Y-m-d'));
	    	$stmt->bindValue(':status', 0);

	    	if($stmt->execute()){
	    		return true;
	    	}else{
	    		return false;
	    	}
    	}catch(\Throwable $e){
    		echo json_encode(['error' => true, 'message' => 'User already has a lease']);
    	}

    	
    }

    public function lease_data($id){

    	$stmt = $this->db->prepare("SELECT * FROM leases WHERE id = :id");
            $stmt->bindValue(':id', $id);

        if($stmt->execute()){

           $bal = $stmt->fetch(PDO::FETCH_ASSOC);
           return $bal;

        }else{
            return false;
    	}
    }


    public function retrieve_fund($id, $table, $amount){

    	$this->db->beginTransaction();
    	$stmt = $this->db;
		try {

			$userModel = new UserModel();

			$cur_bal = $userModel->last_finance_record($table, $id);
			$user = $userModel->get_user($id);

			$new_bal = $cur_bal - $amount;

		    $stmt = $stmt->prepare("INSERT INTO $table (fname, lname, transaction_code, amt_transacted, bal_before, bal_after, date_time, id) VALUES (:fname, :lname, :transaction_code, :amt_transacted, :bal_before, :bal_after, :date_time, :id)");
	        $stmt->bindValue(':fname', $user['fname']);
	        $stmt->bindValue(':lname', $user['lname']);
	        $bytes = random_bytes(4);        
	        $stmt->bindValue(':transaction_code', 'MJF'.bin2hex($bytes));        
	        $stmt->bindValue(':amt_transacted', $amount);       
	        $stmt->bindValue(':bal_before', $cur_bal);       
	        $stmt->bindValue(':bal_after', $new_bal);        
	        $stmt->bindValue(':date_time', date('Y-m-d h:i:s'));        
	        $stmt->bindValue(':id', $id);

	        if($stmt->execute()){
	        	$this->db->commit();
	            return /*$this->last_payment_record($table, $id);*/ true;
	            // exit;
	        }else {
	            return false;
	        }  
		} catch(\Throwable $e) { // use \Exception in PHP < 7.0
		   $this->db->rollBack();
		    throw $e;
		}
	}

	public function pay_lease($id, $amount, $m){

		$this->db->beginTransaction();
		$stmt = $this->db;

		try{

			$lease = $this->lease_data($id);
			$bal = $lease['balance'] + $amount;

			$stmt = $stmt->prepare("UPDATE leases SET balance = :bal WHERE id = :id");
			$stmt->bindValue(':bal', $bal);
			$stmt->bindValue(':id', $id);

			if($stmt->execute()){

				$stmt = $this->db->prepare("INSERT INTO lease_payments (user_id, lease_id, bal_before, amount, bal_after, method, date) VALUES (:user_id, :lease_id, :bal_before, :amount, :bal_after, :method, :date)");

				$stmt->bindValue(':user_id', $id);
				$stmt->bindValue(':lease_id', $lease['id']);
				$stmt->bindValue(':bal_before', $lease['balance']);
				$stmt->bindValue(':amount', $amount);
				$stmt->bindValue(':bal_after', $lease['balance'] + $amount);
				$stmt->bindValue(':method', $m);
				$stmt->bindValue(':date', date('Y-m-d'));

				if($stmt->execute()){
					$this->db->commit();
					echo "string";
					return true;
				}else{
					return false;
				}
				
			}else{
				return false;
			}

		}catch (\Throwable $e){
			$this->db->rollBack();

			throw $e;
		}
	}

/*
	public function last_payment_record($table, $id){

        $stmt = $this->db->prepare("SELECT bal_after FROM $table WHERE id = :id ORDER BY trans_id DESC LIMIT 1");
        $stmt->bindValue(':id', $id);

        if($stmt->execute()){
            $bal_after = $stmt->fetch(PDO::FETCH_ASSOC);
            return $bal_after['bal_after'];
        }else{
            return false;
        }
        
    }
*/
}