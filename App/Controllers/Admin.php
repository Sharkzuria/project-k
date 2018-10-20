<?php

namespace App\Controllers;

use \Core\View;
use App\Models\AdminModel;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Admin extends \Core\Controller
{

    private $aModel;

    function __construct(){

        $this->aModel = new AdminModel();
    }


    public function adminDash(){
        // $data = trim(file_get_contents('php://input'));
        // $data = json_decode($data, false);
        // $id = $data->id;
        if(!isset($_SESSION['user']['userId']) || !isset($_SESSION['user']['level']) || $_SESSION['user']['level'] < 5){

            redirect('/admin/login');

        }else{
             $id = $_SESSION['user']['userId'];

            $userData['user'] = $this->aModel->get_admin($id);
            $lease = $this->aModel->get_all_leases();
            $userData['lease'] = $lease[0];
            // print_r($userData); exit;
            View::renderTemplate('Admin/admin-dash.php', $userData);
        }
       
    }

    public function allPayments(){

         if(!isset($_SESSION['user']['userId']) || !isset($_SESSION['user']['level']) || $_SESSION['user']['level'] < 5){

            redirect('/admin/login');

        }else{
             $id = $_SESSION['user']['userId'];

            $userData['user'] = $this->aModel->get_admin($id);
            $userData['payments'] = $this->aModel->get_payments("");

            // print_r($userData); exit;
            View::renderTemplate('Admin/view-payments.php', $userData);
        }
    }

    public function allUsers(){

         if(!isset($_SESSION['user']['userId']) || !isset($_SESSION['user']['level']) || $_SESSION['user']['level'] < 5){

            redirect('/admin/login');

        }else{
             $id = $_SESSION['user']['userId'];

            $userData['user'] = $this->aModel->get_admin($id);
            $userData['payments'] = $this->aModel->get_users("");

            // print_r($userData); exit;
            View::renderTemplate('Admin/view-users.php', $userData);
        }
    }



    /**
     * Show the index page
     *
     * @return void
     */
    public function loginAction()
    {
        if($_SERVER['REQUEST_METHOD'] == "GET"){

            if(isset($_SESSION['user']['userId'])){
                redirect('/admin/dashboard');
            }else{
                View::renderTemplate('Admin/login.php');
            }
           

        }else if($_SERVER['REQUEST_METHOD'] == "POST"){

            /* $email = "";
            $password = "";
            $login_data = trim(file_get_contents('php://input'));
            $login_data = json_decode($login_data, false);
            if(is_object($login_data)){
                $email = $login_data->email;
                $password = $login_data->password;
            }else{
                echo json_encode(['status' => 403]);
            }*/
            // print_r($login_data->email);
            // $login_data = get_object_vars($login_data);
            $user = '';
            if(isset($_POST['email']) && isset($_POST['password'])){

                $user = $this->aModel->login($_POST['email'], $_POST['password']);
                if(!$user['error']){
                /*$tokenId = base64_encode(openssl_random_pseudo_bytes(32));
                $issuedAt = time();
                $notBefore = $issuedAt + 10;
                $expire = $notBefore + 60;
                $serverName = Config::JWT_INFO['serverName'];
                
                $data = [
                        // 'iat' => $issuedAt,
                        'jti' => $tokenId,
                        // 'iss' => $serverName,
                        // 'nbf' => $notBefore,
                        // 'exp' => $expire,
                        'data' => [
                            'userId' => $user[0],
                            'fname' => $user[1],
                            'lname' => $user[2],
                            'email' => $user[3],
                            'phone' => $user[4],
                        ]
                    ];

                $secretKey = base64_encode(Config::JWT_INFO['jwt']['key']);
                $algorithm = Config::JWT_INFO['jwt']['algorithm'];
                $jwt = JWT::encode($data, $secretKey, $algorithm);

                $unencodedArr = ['jwt' => $jwt, 'status' => 201];*/

                // echo json_encode(['status' => 201]);

                /*echo json_encode($unencodedArr);*/
                $_SESSION['user'] = [
                            'userId' => $user[0],
                            'fname' => $user[1],
                            'email' => $user[2],
                            'level' => $user[3],
                        ];
                if(isset($_SERVER['HTTP_REFERER'])){
                    redirect($_SERVER['HTTP_REFERER']);
                }else{
                    redirect('/admin/dashboard');
                }
                
            } else {

                echo json_encode(['status' => 403]);
            }
            }
        }
       
    }

}
