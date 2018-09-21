<?php

class WebUsers {

    public static function isUser() {
        $userRole = config('constant.roles.USER');
        if (Auth::user()->role_id == $userRole) {
            return true;
        }
        return false;
    }

    public static function isAdmin() {
        $adminRole = config('constant.roles.ADMIN');
        if (Auth::user()->role_id == $adminRole) {
            return true;
        }
        return false;
    }
}
