<?php 
    class Util {
        public function getToken($length) {
            return bin2hex(random_bytes($length));
        }

        public function redirect($url) {
            header("Location:" . $url);
            exit;
        }
        
        public function clearAuthCookies() {
            if (isset($_COOKIE["user"])) {
                setcookie("user_login", "", 0);
            }
            if (isset($_COOKIE["random_pwd"])) {
                setcookie("random_password", "");
            }
            if (isset($_COOKIE["random_selector"])) {
                setcookie("random_selector", "");
            }
        }
    }
?>