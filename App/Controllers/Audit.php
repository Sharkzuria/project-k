<?php

namespace App\Controllers;

use \Core\View;
use App\Models\AuditModel;
use Firebase\JWT\JWT;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Audit extends \Core\Controller
{
    private $con;

    function __construct(){

        $this->con = new AuditModel();
    }

    public function confirmUser(){
    	$id = 1;
    	$table = 'mpesa_records';
    	$table2 = 'bank_records';
    	$confirmation_data = array(
    			'error' => false,
    			'confirmation'=> false,
    			'housing_class' => 'D',
    			'cost' => 500000,
    			'location' => 'kitengela',
    			'least_pay' => 0,
    			'tax_charge' => 3.0
    		);
    	//check user financials average 
    	$m_avg = $this->con->get_avg($id, $table);
    	$b_avg = $this->con->get_avg($id, $table2);

    	$m_avg = ($m_avg)? $m_avg : 0;
    	$b_avg = ($b_avg)? $b_avg : 0;

    	$avg = $m_avg + $b_avg;

    	// if 30% of average is >= 3000 user can be shortlisted fro a hours
    	$minPerc = (30 * $avg) / 100;

    	if($minPerc >= 3000){

    		$confirmation_data['confirmation'] = true;

    		//check user housing level
    		if($minPerc >= 15000 && $minPerc < 20000){

    			$confirmation_data['housing_class'] = 'C';
    			$confirmation_data['cost'] = 1500000;
    			$confirmation_data['tax_charge'] = 4.0;
    			$confirmation_data['location'] = 'Langata';
    			$confirmation_data['least_pay'] = $minPerc;

    		}elseif ($minPerc >= 20000 && $minPerc < 55000) {

    			$confirmation_data['housing_class'] = 'B';
    			$confirmation_data['tax_charge'] = 6.0;
    			$confirmation_data['cost'] = 3500000;
    			$confirmation_data['least_pay'] = $minPerc;
    			$confirmation_data['location'] = 'Westlands';

    		}elseif ($minPerc >= 55000/* && $minPerc < 50000*/) {
    			$confirmation_data['housing_class'] = 'A';
    			$confirmation_data['tax_charge'] = 4.0; //charge less on tax due to the high amount this class pays
    			// that subsidises for the poorer people
    			$confirmation_data['cost'] = 7000000;
    			$confirmation_data['least_pay'] = $minPerc;
    			$confirmation_data['location'] = 'Downtown Nairobi';
    		}
    		//sign user up for housing allocation

    		if($this->con->add_lease($id, $confirmation_data)){

    			echo json_encode(['error'=> false, 'message' => 'Lease Added']);
    		}else{
    			echo json_encode(['error'=> true, 'message' => 'Lease Not Added']);
    		}

    	} else{
    		echo json_encode(['error'=> true, 'message' => 'User Does Not Meet Crieteria']);
    	}


    	// if user defaults for 6 months, put them on probation
    	// set user for eviction after another 3 months if default continues
    	//
    }

    /*
    *Lease payments
    *
    */

    public function leasePayments(){
    	$id = 1;
    	$amount = 3000;
	    //get user lease bal from lease table
	    $lease_bal = $this->con->lease_data($id);
	    $lease_bal = $lease_bal['balance'];

	    // if lease < 0 allow user to make a payment
	    if($lease_bal < 0){ //user hasn't completed pay

			// record the payment if financial records says the user has the amount they intend to pay
			$m_cur_finance_bal = $this->con->get_cur_finance_bal($id, 'mpesa_records');
			$b_cur_finance_bal = $this->con->get_cur_finance_bal($id, 'bank_records');

		    $m_cur_finance_bal = ($m_cur_finance_bal)? $m_cur_finance_bal : 0;
	    	$b_cur_finance_bal = ($b_cur_finance_bal)? $b_cur_finance_bal : 0;

		    $tot_bal = $m_cur_finance_bal + $b_cur_finance_bal;
		    
		    if($tot_bal >= $amount){

		    	// if user cant pay out the lease on one account, then pay
		    	if(($b_cur_finance_bal - $amount) < 0){

		    		$rem_amt = $b_cur_finance_bal - $amount;
		    		$rem_amt *= -1; //convert remainder to positive
		    		$amt_from_b_acc = $amount - $rem_amt; //amount from bank

		    		$amt_from_m_acc = $amount - $amt_from_b_acc; //amount from mpesa

		    		if($amt_from_b_acc > 0){
		    		// take out money from bank
		    			if($this->con->retrieve_fund($id, 'bank_records', $amt_from_b_acc)){
		    				// record payment to lease
		    				$lease_det = $this->con->pay_lease($id, $amount,'b');

		    				if($lease_det){
		    					echo json_encode(['error'=> false, 'message' => 'Success']);
		    				}else{
		    					// error
		    				}

		    			}else{
		    				// error
		    			}

		    		}

		    		if($amt_from_m_acc > 0){
		    			//take out remainder from mpesa
		    			if($this->con->retrieve_fund($id, 'mpesa_records', $amt_from_m_acc)){
		    				// record payment to lease
		    				$lease_det = $this->con->pay_lease($id, $amount,'m');

		    				if($lease_det){
		    					echo json_encode(['error'=> false, 'message' => 'Success']);
		    				}else{
		    					// error
		    				}

		    			}else{
		    				// error
		    			}

		    		}
		    		
		    	}else{// else take out some of the payment from one accounts

		    		//take out remainder from mpesa
	    			if($this->con->retrieve_fund($id, 'bank_records', $amount)){
	    				// record payment to lease
	    				$lease_det = $this->con->pay_lease($id, $amount,'b');

	    				if($lease_det){
	    					echo json_encode(['error'=> false, 'message' => 'Success']);
	    				}else{
	    					// error
	    				}

	    			}else{
	    				// error
	    			}
		    	}

		    }else{

		    	echo json_encode(['error'=> true, 'message' => 'You need to deposit more money']);
		    }

	    }
	    // record euction in the right table
	    // update lease table
    }
 	
 	/*
    *Calculate min payment for the month
    *
    */

    public function leastMonthsPayment(){

	    // get user financials for the current month
	    // calculate 30% of the current month
	    // 
    }

}