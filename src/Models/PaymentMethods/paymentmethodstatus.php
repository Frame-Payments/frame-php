<?php
namespace Frame\Models\PaymentMethods;
enum PaymentMethodStatus: string {
    case ACTIVE = 'active';
    case BLOCKED = 'blocked';
}