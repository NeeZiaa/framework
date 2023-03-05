<?php

namespace App\Controller;

use App\Models\ExampleModel;
use NeeZiaa\Attributes\Route;
use NeeZiaa\Components\Parser;
use NeeZiaa\Controller;
use NeeZiaa\Http\Response;

class HomeController extends Controller
{

    private ExampleModel $model;

    /**
     * @return void
     * @throws \NeeZiaa\Stream\ParserException
     */
    #[Route(method: "GET", path: "/", name: "home")]
    public function index()
    {
        $input = array('hey', 'test', 'bye');
        $parser = (new Parser($input, "array"))->add('test2');
        (new Response())
            ->setBody("Hello")
            ->setStatus(201)
            ->build()
            ->send();
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