<?php
declare(strict_types=1);
namespace Frame\Models\Refunds;

enum RefundReason: string {
    case DUPLICATE = 'duplicate';
    case FRADULENT = 'fradulent';
    case REQUESTED = 'requested_by_customer';
    case EXPIRED = 'expired_uncaptured_charge';
}