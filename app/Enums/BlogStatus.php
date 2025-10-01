<?php

namespace App\Enums;

enum BlogStatus: string
{
    case PENDING = 'pending';
    case PUBLISHED = 'published';
    case REJECTED = 'rejected';
}
