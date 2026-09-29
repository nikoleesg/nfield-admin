<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Enums;

/**
 * The HTTP method a *REQUEST command configuration calls its URI with.
 */
enum RequestHttpMethodEnum: int
{
    case Get = 1;
    case Post = 2;
    case Put = 3;
    case Delete = 4;
    case Patch = 5;
}
