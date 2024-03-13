<?php
namespace App\Enums;
use Kongulov\Traits\InteractWithEnum;

enum TaskStatus : int{
    use InteractWithEnum;


    case Todo =1;
    case Waiting=2;

    case Done = 3;
}
