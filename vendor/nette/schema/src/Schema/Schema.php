<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com).
 */

declare(strict_types=1);

namespace Nette\Schema;

interface Schema
{
    /**
     * Normalization.
     *
     * @return mixed
     */
    public function normalize(mixed $value, Context $context);

    /**
     * Merging.
     *
     * @return mixed
     */
    public function merge(mixed $value, mixed $base);

    /**
     * Validation and finalization.
     *
     * @return mixed
     */
    public function complete(mixed $value, Context $context);

    /**
     * @return mixed
     */
    public function completeDefault(Context $context);
}
