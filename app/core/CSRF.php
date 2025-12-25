<?php
// app/core/CSRF.php

class CSRF
{
    public static function token(): string
    {
        $t = Session::get('_csrf');
        if (!$t) {
            $t = bin2hex(random_bytes(16));
            Session::set('_csrf', $t);
        }
        return $t;
    }

    public static function verify(?string $token): bool
    {
        $saved = Session::get('_csrf');
        return is_string($saved) && is_string($token) && hash_equals($saved, $token);
    }
}
