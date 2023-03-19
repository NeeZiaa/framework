<?php

namespace App\Controller;

use App\Models\ExampleModel;
use NeeZiaa\AbstractController;
use NeeZiaa\Attributes\Route;
use NeeZiaa\User;

class HomeController extends AbstractController
{

    private ExampleModel $model;

    #[Route(method: "GET", path: "/", name: "home")]
    public function index()
    {
//        $user = (new User())->getByID(1)->getProps();
//        $user2 = (new User())->getProps();
//        $a = (object)['email'=>'hello'];
//        $user = new User();
//        dd($user->getUser());
//        $user->setUser();
//        if($_SERVER['REQUEST_METHOD'] == "POST") {
//            new FieldsChecker();
//        }
        $this->render('test');
//        echo $twig->render('test.html.twig', []);
    }

    public function login()
    {
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password']))
        {
            if(!empty($user = $this->user->get_user_by_name($_POST['username'])))
            {
                if(password_verify($_POST['password'], $user['password']))
                {
                    $user = $this->user->get_user();
                    unset($user['password']);
                    $user['permissions'] = $this->permissions->get_user_permissions();
                    echo json_encode($user);
                    http_response_code(200);
                } else {
                    echo "Wrong password";
                    http_response_code(403);
                }
            } else {
                echo "User not found";
                http_response_code(404);
            }
        } else {
            echo "Missing parameters";
            http_response_code(400);
        }
    }

}