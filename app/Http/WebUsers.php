<?php

class WebUsers {

    public static function isUser() {
        $userRole = config('constant.roles.USER');
        if (Auth::user()->role_id == $userRole) {
            return true;
        }
        return false;
    }
}
