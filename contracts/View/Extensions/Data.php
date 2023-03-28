<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  View Data Wrapper
 *
 */

namespace Contracts\View\Extensions;

use ArrayAccess;
use Closure;
use Countable;
use Iterator;
use Contracts\Support\Arrayable;

interface Data extends Arrayable, ArrayAccess, Countable, Iterator
{ }
