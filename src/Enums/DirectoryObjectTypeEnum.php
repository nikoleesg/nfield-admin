<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Enums;

/**
 * The kind of directory (Entra ID) object a survey group is assigned to.
 */
enum DirectoryObjectTypeEnum: int
{
    case Unknown = 0;
    case User = 1;
    case ServicePrincipal = 2;
    case SecurityGroup = 3;
}
