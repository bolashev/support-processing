<?php

namespace App\Enums;

enum UserRole: string
{
    case SupportManager = 'support_manager';
    case Admin = 'admin';
}
