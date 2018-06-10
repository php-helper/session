<?php
/**
 * Created by PhpStorm.
 * User: Oleg G.
 * Date: 27.05.2018
 * Time: 10:40
 */

namespace PhpHelper\Session\Enums;

use MyCLabs\Enum\Enum;

class FlashMessageEnum extends Enum
{
    const MESSAGE = 'message';
    const WARNING = 'warning';
    const ERROR = 'error';
}