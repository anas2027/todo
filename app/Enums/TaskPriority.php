<?php
namespace App\Enums;
use Kongulov\Traits\InteractWithEnum;

 enum TaskPriority : int
 {
    use InteractWithEnum;

   case low = 1;
   case medium = 2;
   case high = 3;

 }
