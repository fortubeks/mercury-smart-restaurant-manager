<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case MANAGER = 'manager';
    case WAITER = 'waiter';
    case CHEF = 'chef';
    case CASHIER = 'cashier';
}
