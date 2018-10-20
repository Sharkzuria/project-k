<?php

namespace App\Models;

use PDO;


/**
 * Example user model
 *
 * PHP version 7.0
 */
class UserModel extends \Core\Model
{
    private $db;

    function __construct(){
        $this->db = static::connect();
    }
    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public function login($email, $password)
    {   
        
        $result = [];
        $result['error'] = true;
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email);
        //print_r( hashSSHA($password));
        if($stmt->execute()){
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $checkHash = $this->checkhashSSHA($user['salt'], $password);
            if($checkHash == $user['hash']){
                $result['error'] = false;
                array_push($result, $user['id']);
                array_push($result, $user['fname']);
                array_push($result, $user['lname']);
                array_push($result, $user['email']);
                array_push($result, $user['phone']);
                array_push($result, $user['dob']);

                // print_r($result);
                return $result;
            }else{
                return $result['error'] = true;
            }
        }
        
    }

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public function register($data)
    {   
        $fname = $data['fname'];
        $lname = $data['lname'];
        $email = $data['email'];
        $phone = $data['phone'];
        $dob = $data['dob'];
        $password = $data['password'];
        $hashAndSalt = $this->hashSSHA($password);

        $salt = $hashAndSalt['salt'];
        $hash = $hashAndSalt['encrypted'];

        $result = [];
        $result['error'] = true;
        $stmt = $this->db->prepare('INSERT INTO users (fname, lname, email, phone, dob, salt, hash) VALUES (:fname, :lname, :email, :phone, :dob, :salt, :hash)');
        $stmt->bindValue(':fname', $fname);
        $stmt->bindValue(':lname', $lname);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':dob', $dob);
        $stmt->bindValue(':salt', $salt);
        $stmt->bindValue(':hash', $hash);
        //print_r( hashSSHA($password));
        if($stmt->execute()){
            $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->bindValue(':email', $email);

            if($stmt->execute()){
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);
                $result['error'] = false;
                array_push($result, $user['id']);
                array_push($result, $user['fname']);
                array_push($result, $user['lname']);
                array_push($result, $user['email']);
                array_push($result, $user['phone']);
                array_push($result, $user['dob']);
                
                return $result;
            }else{
                return $result['error'] = true;
            }
        }
        
    }

    public function addBankAcc($data){

        $result['message'] = "failed";

        if(sizeof($this->myBankAcc($_SESSION['user']['userId'])) > 0){

            $result['message'] = "failed - Can't enter morethan one bank";
            $result['error'] = true;

            return $result;
        }

        $stmt = $this->db->prepare("INSERT INTO bank_accounts (userid, bank, acc_number) VALUES (:userid, :bank, :acc_number)");
        $stmt->bindValue(':userid', $_SESSION['user']['userId']);
        $stmt->bindValue(':bank', $data['bank']);
        $stmt->bindValue(':acc_number', $data['acc_number']);

        if($stmt->execute()){
            $result['message'] = "Success";
            $result['error'] = false;

            return $result;
        }else{
            $result['message'] = "failed - Can't enter morethan one bank";
            $result['error'] = true;

            return $result;
        }
    }

    public function myBankAcc($id){

        $stmt = $this->db->prepare("SELECT * FROM bank_accounts WHERE userid = :id");
        $stmt->bindValue(':id', $id);

        if($stmt->execute()){
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            return false;
        }

    }

    public function add_payment_record($table, $amount, $bal_before, $id, $fname, $lname, $bank_id = null){

        $stmt = "";

        if($table == 'bank_records'){

            $stmt = $this->db->prepare("INSERT INTO $table (fname, lname, transaction_code, amt_transacted, bal_before, bal_after, bank_id, date_time, id) VALUES (:fname, :lname, :transaction_code, :amt_transacted, :bal_before, :bal_after, :bank_id, :date_time, :id)");
            $stmt->bindValue(':bank_id', $bank_id);

        }else{
            $stmt = $this->db->prepare("INSERT INTO $table (fname, lname, transaction_code, amt_transacted, bal_before, bal_after, date_time, id) VALUES (:fname, :lname, :transaction_code, :amt_transacted, :bal_before, :bal_after, :date_time, :id)");
        }
        
        
        $stmt->bindValue(':fname', $fname);
        $stmt->bindValue(':lname', $lname);        
        $bytes = random_bytes(4);      
        $stmt->bindValue(':transaction_code', 'MJF'.bin2hex($bytes));  
        $stmt->bindValue(':amt_transacted', $amount);       
        $stmt->bindValue(':bal_before', $bal_before);       
        $stmt->bindValue(':bal_after', $bal_before + $amount);        
        $stmt->bindValue(':date_time', date('Y-m-d h:i:s'));        
        $stmt->bindValue(':id', $id);

        if($stmt->execute()){
            return $this->last_finance_record($table, $id);
            // exit;
        }else {
            return false;
        }  
    }

    public function last_finance_record($table, $id){

        $stmt = $this->db->prepare("SELECT bal_after FROM $table WHERE id = :id ORDER BY trans_id DESC LIMIT 1");
        $stmt->bindValue(':id', $id);

        if($stmt->execute()){
            $bal_after = $stmt->fetch(PDO::FETCH_ASSOC);
            return $bal_after['bal_after'];
        }else{
            return false;
        }
        
    }

    public function getFinances($id){
        $result = '';
        $result['mpesa']['expenses'] = [];
        $result['mpesa']['income'] = []; 
        $result['bank']['expenses'] = [];
        $result['bank']['income'] = [];

        $stmt = $this->db->prepare("SELECT amt_transacted AS m_ex FROM `mpesa_records` WHERE amt_transacted < 0 AND id = :id");
        $stmt->bindValue(':id', $id);

        if($stmt->execute()){

            $mpesa = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($mpesa as $row) {
                array_push($result['mpesa']['expenses'], $row['m_ex']);
            }
            
        }

        $stmt = $this->db->prepare("SELECT amt_transacted AS m_in FROM `mpesa_records` WHERE amt_transacted > 0 AND id = :id");
        $stmt->bindValue(':id', $id);

        if($stmt->execute()){

            $mpesa = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($mpesa as $row) {
                array_push($result['mpesa']['income'], $row['m_in']);
            }
            
        }

        $stmt = $this->db->prepare("SELECT amt_transacted AS b_in FROM `bank_records` WHERE amt_transacted > 0 AND id = :id");
        $stmt->bindValue(':id', $id);

        if($stmt->execute()){

            $mpesa = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($mpesa as $row) {
                array_push($result['bank']['income'], $row['b_in']);
            }
            
        }

        $stmt = $this->db->prepare("SELECT amt_transacted AS b_ex FROM `bank_records` WHERE amt_transacted < 0 AND id = :id");
        $stmt->bindValue(':id', $id);

        if($stmt->execute()){

            $mpesa = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($mpesa as $row) {
                array_push($result['bank']['expenses'], $row['b_ex']);
            }
            
           
        }

        // echo "<pre>" . print_r($result, true) ."<\pre>";
        return $result;

    }

    public function get_payments($id){/*

        if($id === ""){
            $stmt = $this->db->query("SELECT lease_payments.bal_before, lease_payments.amount, lease_payments.bal_after, lease_payments.method, lease_payments.date, users.fname FROM lease_payments LEFT JOIN users ON users.id = lease_payments.user_id");

            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }else{*/

            $stmt = $this->db->prepare("SELECT lease_payments.bal_before, lease_payments.amount, lease_payments.bal_after, lease_payments.method, lease_payments.date, users.fname FROM lease_payments LEFT JOIN users ON users.id = lease_payments.user_id WHERE lease_payments.user_id = :id");
            $stmt->bindValue(":id", $id);

            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        /*}*/
        
    }

    public function get_user($id){
        $stmt = $this->db->prepare("SELECT fname, lname, email, phone, dob, activated FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id);

        if($stmt->execute()){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
        
    }

  public function get_leases($id){

        if($id != ""){

            $stmt = $this->db->query("SELECT * FROM leases");

            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }else{

            $stmt = $this->db->prepare("SELECT * FROM leases WHERE id = :id");
            $stmt->bindValue(':id', $id);

            if($stmt->execute()){
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }
        
        
    }

    /**
    *Encrypting password
    *@param password
    *returns salt and encrypted password
    */

    private function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */

    private function checkhashSSHA($salt, $password){

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }


    
}
