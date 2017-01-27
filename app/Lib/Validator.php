<?php

namespace App\Lib;

class Validator {

    const MOD_10 = 10;

    /**
     * Return true if card number is correct. Return false if not
     * @param type $cardNumber
     * @return boolean
     */
    public static function cardNumberValid($cardNumber) {
        if (0 === strlen($cardNumber)) {
            return false;
        }
        $cardArray = str_split($cardNumber, 1);
        $cardArraySize = count($cardArray);
        $controlSum = $tmp = 0;
        for ($index = $cardArraySize - 2; $index >= 0; $index-=2) {
            $tmp = intval($cardArray[$index]);
            $tmp *= 2;
            if ($tmp > 9) {
                $splited = str_split(strval($tmp), 1);
                $tmp = intval($splited[0]) + intval($splited[1]);
            }
            $controlSum += $tmp;
        }
        if ($controlSum % static::MOD_10 === 0) {
            return true; //Card number is correct
        }
        return false; //Card number is wrong
    }

    /**
     * Return true if expiration date is correct. Return false if not.
     * @param type $expirationDate
     * @return boolean
     */
    public static function validateExpirationDate($expirationDate) {
        $regExpCheck = preg_match('/^[0-9]{2}\/[0-9]{2}$/', $expirationDate);
        if (0 === $regExpCheck || FALSE === $regExpCheck ) {
            return false;
        }
        $splitDate = explode('/', $expirationDate);
        if (count($splitDate) > 2) { //There should be only two elements
            return false;
        }
        $userDate = '20' . $splitDate[1] . '-' . $splitDate[0]; //I`m aware that it will be only true till 2100. :) 
        $expDate = date("Y-m-d", strtotime($userDate));
        if ($expDate > date("Y-m-d")) {
            return true;
        }
        return false;
    }

    /**
     * Return true if email is correct. Return false if not.
     * @param type $email
     */
    public static function validateEmail($email) {
        $emailCheck = preg_match('	
/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/', $email);
        if(0 === $emailCheck|| FALSE === $emailCheck) {
            return false;
        }
        return true;
    }
    
    /**
     * Phone number validation. Return true if number is correct, return false if it's not correct.
     * @param type $phone
     */
    public static function validatePhoneNumber($phone) {
        $regexp = '/^[0-9]{3}\-[0-9]{3}\-[0-9]{3}$/';
        $check = preg_match($regexp, strval($phone));
        if(0 === $check  || FALSE === $check) {
            return false;
        }
        return true;
    }

}
