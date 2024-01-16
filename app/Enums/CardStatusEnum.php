<?php 

namespace App\Enums;

enum CardStatusEnum: string {
    case PROCESSING = 'processing';
    case RELEASED = 'released';
}