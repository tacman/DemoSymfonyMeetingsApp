<?php

namespace App\Entity;

enum Status: string
{
    case OPEN_TO_REGISTRATION = 'open to registration';
    case FULL = 'full';
    case IN_SESSION = 'in session';
    case DONE = 'done';
}
