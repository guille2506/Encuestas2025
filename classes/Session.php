<?php
    class user_session{
        Public function __construct(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }   
        Public function SetSessionElement($element,$data){
            $_SESSION[$element] = $data;
        }
        Public function GetSessionElement($element) {
            if(isset($_SESSION[$element])){
                return $_SESSION[$element];
            }else{
                return false;
            }
        }
        Public function GetAllSessionElement() {
            return $_SESSION;
        }
        Public function deleteinfo(){
            $preservar = 'cantidadForm';
            foreach ($_SESSION as $clave => $valor) {
                if ($clave !== $preservar) {
                    unset($_SESSION[$clave]);
                }
            }
        }
        Public function destroySession() {
            session_destroy();
        }
    }

    if(isset($_POST["s"])){
        $text = explode("-",$_POST["s"]);
        $id = $text[0];
        $data = $text[1];
        $s = new user_session;
        $s->SetSessionElement($id,$data);
        echo json_encode([
            'id' => $id,
            'data' => $data
        ]);
        exit;
    }
    if(isset($_POST["g"])){
        $s = new user_session;
        $respuesta = $s->GetSessionElement($_POST["g"]);
        echo json_encode([
            'data' => $respuesta
        ]);
        
        exit;
    }
    if(isset($_POST["gall"])){
        $s = new user_session;
        $arrSession = $s->GetAllSessionElement();
        $respuesta = "";
        foreach($arrSession as $clave => $elem){
            $respuesta = $respuesta . "//" . $clave ."||". $elem; 
        }
        echo json_encode([
            'data' => $respuesta
        ]);
    }
    if(isset($_POST["d"])){
        $s = new user_session;
        $arrSession = $s->deleteinfo();
        echo json_encode([
            'data' => "eliminado"
        ]);
    }