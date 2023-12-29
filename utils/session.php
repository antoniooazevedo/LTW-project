<?php
    class Session{ 
        public function __construct(){
            session_start();
        }
        public function setUsername(string $username){
            $_SESSION['username'] = $username;
        }
        public function getUsername() : ?string{
            return isset($_SESSION['username']) ? $_SESSION['username'] : null;
        }
        public function setLogin(){
            $_SESSION['login'] = true;
        }
        public function isLoggedIn() : bool {
            return isset($_SESSION['login']) && $_SESSION['username'] != null;
        }
        public function logout() {
            session_destroy();
        }

        public function generateToken(){
            if (!isset($_SESSION['csrf'])){
                $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
            }
        }
    }
?>