<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com).
 */

declare(strict_types=1);

namespace Nette\Utils;

use const IMAGETYPE_BMP;
use const IMAGETYPE_GIF;
use const IMAGETYPE_JPEG;
use const IMAGETYPE_PNG;
use const IMAGETYPE_WEBP;

/**
 * Type of image file.
 */
/*enum*/ final class ImageType
{
    public const
        JPEG = IMAGETYPE_JPEG;
    public const
        PNG = IMAGETYPE_PNG;
    public const
        GIF = IMAGETYPE_GIF;
    public const
        WEBP = IMAGETYPE_WEBP;
    public const
        AVIF = 19;
    public const
        // IMAGETYPE_AVIF,
        BMP = IMAGETYPE_BMP;
}
