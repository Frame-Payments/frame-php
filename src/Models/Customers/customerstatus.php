<?php
namespace Frame\Models\Customers;
enum CustomerStatus: string {
    case ACTIVE = 'active';
    case BLOCKED = 'blocked';
}