<?php
namespace Frame\Models\PaymentMethods;
enum PaymentMethodType: string {
    case CARD = 'card';
    case ACH = 'ach';
}