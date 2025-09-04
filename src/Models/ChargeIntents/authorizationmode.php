<?php
namespace Frame\Models\ChargeIntents;
enum AuthorizationMode: string {
    case AUTOMATIC = 'automatic';
    case MANUAL = 'manual';
}