<?php

namespace App\Enums;

enum PurchaseStatus: string
{
    case PENDING = 'pending';
    case ORDERED = 'ordered';
    case RECEIVED = 'received';

    public function label(): string
    {
        return match($this) {
            PurchaseStatus::PENDING => 'dark',
            PurchaseStatus::ORDERED => 'light',
            PurchaseStatus::RECEIVED => 'green',
        };
    }
}
