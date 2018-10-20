<?php

namespace App\Controllers;

use \Core\View;
use App\Models\UserModel;
use App\Config;
use Firebase\JWT\JWT;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class User extends \Core\Controller
{
    private $uModel;

    function __construct(){

            $this->uModel = new UserModel();

    }

    public function userDash(){
        // $data = trim(file_get_contents('php://input'));
        // $data = json_decode($data, false);
        // $id = $data->id;
        if(!isset($_SESSION['user']['userId'])){

            View::renderTemplate('login.php');

        }else{
             $id = $_SESSION['user']['userId'];

            $userData['user'] = $this->uModel->get_user($id);
            $lease = $this->uModel->get_leases($id);
            $userData['lease'] = $lease[0];
            // print_r($userData); exit;
            View::renderTemplate('Home/index.php', $userData);
        }
       
    }



    public function addBankAccount(){

        if(!isset($_SESSION['user']['userId'])){

            View::renderTemplate('login.php');

        }else{
            if($_SERVER['REQUEST_METHOD'] == "GET"){
                $id = $_SESSION['user']['userId'];

                $template['message'] = (isset($_SESSION['message']))? $_SESSION['message']: "";
                $template['class'] = (isset($_SESSION['class']))? $_SESSION['class'] : "" ;
                $template['user'] = $this->uModel->get_user($id);

                View::renderTemplate('User/add-account.php', $template);
                $_SESSION['message'] = "";

            }else if($_SERVER['REQUEST_METHOD'] == "POST"){

                $result = $this->uModel->addBankAcc($_POST);
                if(!$result['error']){

                    $_SESSION['message'] = 'Success!!';
                    $_SESSION['class'] = 'Success';
                    redirect('/user/add-bank-account');

                }else{
                    $_SESSION['message'] = $result['message'];
                    redirect('/user/add-bank-account');
                }
            }
        }
    }

    public function userPayments(){

            if(!isset($_SESSION['user']['userId'])){

                View::renderTemplate('login.php');

            }else{

                $id = $_SESSION['user']['userId'];

                $userData['user'] = $this->uModel->get_user($id);
                $userData['payments'] = $this->uModel->get_payments($id);
                // print_r($userData); exit();
                View::renderTemplate('User/view-my-payments.php', $userData);
            }
    

    }


    public function seed(){
        $typeM = [0, 1, 2, 3, 4];
        $typeB = [5, 2, 3, 4, 5];
        $id = null;
        // $transction_type = $_POST['tr_type'];
        $transction_type = 'bank_records';

        $type = $typeM[0];

        if($transction_type == 'bank_records'){
            $bank_id = $_POST['bank_id'];
            $id = $_POST['id'];
        }else if($transction_type == 'mpesa_records'){
            // $bank_id = $_POST['phone_no'];
            $id = $_POST['id'];
        }

        //get range for number of records
        $range = rand(rand(20, 70), rand(70, 130));
        //get user
        $user = $this->uModel->get_user($id);

        if(!isset($user['fname'])){
            echo json_encode(['error' => true, 'error_r' =>'user does not exist']);
        }else{

            //check last bal_before
            $bal_before = $this->uModel->last_finance_record($transction_type, $id);
            if(!isset($bal_before['bal_after'])){
                $bal_before = 0;
            }else{
                $bal_before = $bal_before['bal_after'];
            }

            $fname = $user['fname'];
            $lname = $user['lname'];

            for($i = 0; $i <= $range; $i ++){
                //get random amount
                $amount = $this->get_strat_rand_amount($type);

                //if last bal_before is 0, or < amount generated, then add amount to account
                if($amount > $bal_before){
                    $bal_before = $this->uModel->add_payment_record($transction_type, $amount, $bal_before, $id, $fname, $lname);

                }else if(($amount < $bal_before) && ((rand(2, 7)%2) == 0) || ((rand(2, 9)%3) == 0)){ //else at rand count,if last balance is > 0, or > amount generated, then subtract amount to account (record an expenditure)
                    $amount = 0 - $amount;
                    $bal_before = $this->uModel->add_payment_record($transction_type, $amount, $bal_before, $id, $fname, $lname);

                }else{//just add
                    $bal_before = $this->uModel->add_payment_record($transction_type, $amount, $bal_before, $id, $fname, $lname);
                }

                
                
            }
        }
    }

    public function getUserFinance(){

        $id = 1;

        $finances =  $this->uModel->getFinances($id);
        // echo "<pre>" . print_r($result, true) ."<\pre>";
        echo json_encode($finances);
    }

    private function get_strat_rand_amount($type): int {

        $amt_range_start = 0;
        $amt_range_stop = 0;

        switch ($type) {
            case 0:
                $amt_range_start = 5;
                $amt_range_stop = 12000;
                break;
            case 1:
                $amt_range_start = 50;
                $amt_range_stop = 30000;
                break;

            case 2:
                $amt_range_start = 50;
                $amt_range_stop = 65000;
                break;
            case 3:
                $amt_range_start = 50;
                $amt_range_stop = 80000;
                break;
            case 4:
                $amt_range_start = 50;
                $amt_range_stop = 100000;
                break;
            
            default:
                break;
        }

        $amount = mt_rand($amt_range_start, $amt_range_stop);

        if(($amount % 2) == 0){
            $amount = $amount / 2;
        }

        return $amount;
    }

    /**
     * Method to generate random date between two dates
     * @param $sStartDate
     * @param $sEndDate
     * @param string $sFormat
     * @return bool|string
     */
    private function randomDate($sStartDate, $sEndDate, $sFormat = 'Y-m-d H:i:s')
    {
        // Convert the supplied date to timestamp
        $fMin = strtotime($sStartDate);
        $fMax = strtotime($sEndDate);
        // Generate a random number from the start and end dates
        $fVal = mt_rand($fMin, $fMax);
        // Convert back to the specified date format
        return date($sFormat, $fVal);
    }

}
