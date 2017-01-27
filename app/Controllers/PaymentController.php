<?php

/**
 * @description PaymentController responsible for validate payment information
 * @author: dyktus
 * @cretedAt 2017-01-04 21:17:39
 * @class PaymentController
 */

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use App\Lib\Validator;

class PaymentController {

    public static $ROUTES = [
        'POST payByCreditCard' => 'payByCreditCard',
        'POST payByMobile' => 'payByMobile'
    ];

    /**
     * Mobile payment process
     * @param Request $request
     * @return type
     */
    public function payByMobile(Request $request) {
        $data = [
            'phone' => $request->get('phone')
        ];
        if (empty($data['phone'])) {
            return [
                'msg' => ['code' => 200, 'msg' => 'Parameter phone cannot be empty'],
                'http_code' => 400
            ];
        }
        if (!Validator::validatePhoneNumber($data['phone'])) {
            return [
                'msg' => ['code' => 201, 'msg' => 'Wrong phone number. It should be in format 000-000-000'],
                'http_code' => 400
            ];
        }
        return [
            'msg' => ['code' => 400, 'msg' => 'Phone payment processed correctly'],
            'http_code' => 200
        ];
    }

    /**
     * Credit card payment process
     * @param Request $request
     * @return type
     */
    public function payByCreditCard(Request $request) {
        $data = [
            'card_number' => $request->get('card_number'),
            'expiration_date' => $request->get('expiration_date'),
            'cvv' => $request->get('cvv'),
            'email' => $request->get('email'),
        ];
        foreach ($data as $k => $v) {
            if (empty($v)) {
                return [
                    'msg' => ['code' => 200, 'msg' => 'Parameter ' . $k . ' cannot be empty'],
                    'http_code' => 400
                ];
            }
        }
        if (!Validator::cardNumberValid($data['card_number'])) {
            return [
                'msg' => ['code' => 202, 'msg' => 'Credit card number is wrong'],
                'http_code' => 400
            ];
        }
        if (!Validator::validateExpirationDate($data['expiration_date'])) {
            return [
                'msg' => ['code' => 203, 'msg' => 'Credit card expired'],
                'http_code' => 400
            ];
        }
        if (!Validator::validateEmail($data['email'])) {
            return [
                'msg' => ['code' => 204, 'msg' => 'E-mail address is wrong'],
                'http_code' => 400
            ];
        }
        return [
            'msg' => ['code' => 401, 'msg' => 'Credit card payment processed correctly.'],
            'http_code' => 200
        ];
    }

}
