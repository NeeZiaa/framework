<?php
namespace NeeZiaa;

use NeeZiaa\Database\QueryBuilder;

class Login{

    public static function signin($name,$surname,$email,$password,$cpassword,$discord){

        if(isset($name) && !empty($name) && isset($surname) && !empty($surname) && isset($email) && !empty($email) && isset($password) && !empty($password) && isset($cpassword) && !empty($cpassword)){
            
            if($password == $cpassword){

                if(!isset($discord) && empty($discord)){

                    $discord = NULL;

                }

                $user = (new QueryBuilder())
                    ->select('id')
                    ->table('users')
                    ->where('email = :email')
                    ->params(['email'=>$email]);

                if($user->count() == 1) : function error(){ echo "Un compte existe déjà avec cet email"; } endif;

                $password = password_hash($password, PASSWORD_ARGON2ID);

                $insertuser = (new QueryBuilder())
                    ->insert(['firstname','surname','email','password','discord','date','active'])
                    ->table('users')
                    ->where('firstname = :firstname, surname = :surname, email = :email, password = :password, discord = :discord, active = :active')
                    ->params(['firstname'=>$name,'surname'=>$surname,'email'=>$email,'password'=>$password,'discord'=>$discord,'active'=>0])
                    ->execute();


                // $insertuser = $db->prepare('INSERT INTO users(name,surname,email,password,discord,date,active) VALUES(:name,:surname,:email,:password,:discord,NOW(),:active)');
                // $insertuser->execute([
                //     'name'=>$name,
                //     'surname'=>$surname,
                //     'email'=>$email,
                //     'password'=>$password,
                //     'discord'=>$discord,
                //     'active'=>0
                // ]);

                $subject = "Confirmation de votre compte Sanctuary";
                $body = "Bonjour, vous vous êtes inscrit avec cet email sur Sanctuary.com. Vous pouvez entrer ce code ou cliquer sur ce lien pour activer et confirmer votre compte";
                $altbody = "";

                \NeeZiaa\Main::send_mail($email,$subject,$body,$altbody);

                header('Location: https://sanctuary-boutique/confirm-'.$email);   

            } else {
                function error(){
                    echo "Les deux mots de passes ne sont pas les mêmes";
                }
            }
        } else {
            function error(){
                echo "Veuillez remplir tous les champs";
            }
        }
    }

    public static function confirm_send($email){

        // création du code

        $c = random_int(100000,999999);

        $code = (new QueryBuilder())
            ->insert(['code','email'])
            ->table('authorizations')
            ->params(['code'=>$c,'email'=>$c])
            ->execute();

        // $code = $db->prepare('INSERT INTO authorizations(code,email,date) VALUES(:code,:email,NOW())');
        // $code->execute([
        //     'code'=>$c,
        //     'email'=>$email
        // ]);

        $subject = "Votre code de confirmation";
        $body = "content ".$c;
        $altbody = "non-html content ".$c;

        \NeeZiaa\main::send_mail($email,$subject,$body,$altbody);

    }

    public static function confirm_code($email,$code){

        if(isset($_POST['code']) && strlen($_POST['code']) == 6){

            $auth = (new QueryBuilder())
                ->select()
                ->table('authorizations')
                ->where('code = :code AND email = :email')
                ->params(['code'=>$code, 'email'=>$email]);

            $a = $auth->fetch();
            // $auth = $db->prepare('SELECT * FROM auth WHERE code = :code AND email = :email');
            // $auth->execute([
            //     'code'=>$code,
            //     'email'=>$email
            // ]);
            // $a = $auth->fetch();

            if($auth->count() == 1){

                $user = (new QueryBuilder())
                    ->select()
                    ->table('users')
                    ->where('email = :email')
                    ->params(['email'=>$email])
                    ->fetchAll();

                // $user = $db->prepare('SELECT * FROM users WHERE email = :email');
                // $user->execute(['email'=>$email]);
                // $u = $user->fetch();

                $activate = (new QueryBuilder())
                    ->update(['active'])
                    ->table('users')
                    ->where('id = :id')
                    ->params(['id'=>$a['id']])
                    ->execute();
                
                $deltoken = (new QueryBuilder())
                    ->delete()
                    ->table('auth')
                    ->where(['id'=>$a['id']])
                    ->execute();

                // $activate = $db->prepare('UPDATE users SET active = 1 WHERE id = :id');
                // $activate->execute(['id'=>$a['id']]);

                // $deltoken = $db->prepare('DELETE FROM auth WHERE id = :id');
                // $deltoken->execute(['id'=>$a['id']]);
                
                $_SESSION['id'] = $user['id'];

                // header('Location: redirection');
            } else {
                function error(){
                    echo "Code invalide";
                }
            }

        } else {
            function error(){
                echo "Code invalide";
            }
        }


    }

    public static function login($email,$password){
        
        $user = (new QueryBuilder())
        ->select()
        ->table('users')
        ->where('email = :email')
        ->params(['email'=>$email]);
        
        $u = $user->fetch();

        if($user->count() == 1){

            if(password_verify($password,$u['password'])){
                if($u['active'] == 1){
                    $_SESSION['id'] = $u['id'];
                } else {
                    function error(){
                        echo "Votre compte n'est pas activé, veuillez vérifier votre email";
                    }
                }
            } else {
                function error(){
                    echo "Mot de passe invalide";
                }
            }

        } else {

            function error(){
                echo "Aucun compte n'existe avec cet email";
            }

        }

    }

    public static function forgot_password($email){

        $subject = "Oubli de mot de passe";
        $body = "";
        $altbody = "";

        \NeeZiaa\Main::send_mail($email,$subject,$body,$altbody);

    }

}