<?php
class AuthService {
    // ... your other functions ...

    public function setTokenCookie($token) {
        setcookie('authToken', $token, time() + 3600, '/', '', false, true); // HTTP-only cookie
    }

    public function getTokenFromCookie() {
        if (isset($_COOKIE['authToken'])) {
            return $_COOKIE['authToken'];
        }
        return null;
    }

    public function clearTokenCookie() {
        setcookie('authToken', '', time() - 3600, '/', '', false, true); // Clear cookie
    }
}
