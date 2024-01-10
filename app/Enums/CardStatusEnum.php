<?php 

namespace App\Enums;

enum CardStatus: string {
    case PROCESSING = 'processing';
    case RELEASED = 'released';
}