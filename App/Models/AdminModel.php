<?php

namespace App\Models;

use PDO;


/**
 * Example user model
 *
 * PHP version 7.0
 */
class AdminModel extends \Core\Model
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
        $stmt = $this->db->prepare('SELECT * FROM admin WHERE email = :email');
        $stmt->bindValue(':email', $email);
        //print_r( hashSSHA($password));
        if($stmt->execute()){
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $checkHash = $this->checkhashSSHA($user['salt'], $password);
            if($checkHash == $user['hash']){
                $result['error'] = false;
                array_push($result, $user['id']);
                array_push($result, $user['fname']);
                array_push($result, $user['email']);
                array_push($result, $user['level']);

                // print_r($result);
                return $result;
            }else{
                return $result['error'] = true;
            }
        }
        
    }


    public function get_admin($id){
        $stmt = $this->db->prepare("SELECT fname, email, level, activated FROM admin WHERE id = :id");
        $stmt->bindValue(':id', $id);

        if($stmt->execute()){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
        
    }
    
    public function get_users(){
        $stmt = $this->db->prepare("SELECT users.fname, users.lname, users.email, users.phone, users.dob, users.activated, leases.cost, leases.balance FROM `users` LEFT JOIN leases ON users.id = leases.userid");

        if($stmt->execute()){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
        
    }

    public function get_all_leases(){

        $stmt = $this->db->query("SELECT SUM(balance) as balance, SUM(cost) AS cost FROM leases");

        if($stmt->execute()){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }

    public function get_payments($id){

        if($id === ""){
            $stmt = $this->db->query("SELECT lease_payments.bal_before, lease_payments.amount, lease_payments.bal_after, lease_payments.method, lease_payments.date, users.fname FROM lease_payments LEFT JOIN users ON users.id = lease_payments.user_id");

            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }else{

            $stmt = $this->db->prepare("SELECT lease_payments.bal_before, lease_payments.amount, lease_payments.bal_after, lease_payments.method, lease_payments.date, users.fname FROM lease_payments LEFT JOIN users ON users.id = lease_payments.user_id WHERE lease_payments.user_id = :id");
            $stmt->bindValue(":id", $id);

            if($stmt->execute()){
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
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