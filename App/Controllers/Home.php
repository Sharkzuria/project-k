<?php

namespace App\Controllers;

use \Core\View;
use App\Models\UserModel;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

	private $uModel;

    function __construct(){

        $this->uModel = new UserModel();
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function registerAction()
    {
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $user = $this->uModel->register($_POST);

            if(!$user['error']){

                $_SESSION['user'] = [
                            'userId' => $user[0],
                            'fname' => $user[1],
                            'lname' => $user[2],
                            'email' => $user[3],
                            'phone' => $user[4],
                        ];

                redirect('/user/dashboard');
            }else{
                echo "error";
            }
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
                redirect('/user/dashboard');
            }{
                View::renderTemplate('login.php');
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

                $user = $this->uModel->login($_POST['email'], $_POST['password']);
                if(!$user['error']){

                print_r($user);
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
                            'lname' => $user[2],
                            'email' => $user[3],
                            'phone' => $user[4],
                        ];
                if(isset($_SERVER['HTTP_REFERER'])){
                	redirect($_SERVER['HTTP_REFERER']);
                }else{
                	redirect('/user/dashboard');
                }
                
            } else {

                echo json_encode(['status' => 403]);
            }
            }
        }
       
    }

	public function logout(){
       unset($_SESSION['user']);

       redirect('/user/login');
    }
}
