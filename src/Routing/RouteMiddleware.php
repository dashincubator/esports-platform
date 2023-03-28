<?php declare(strict_types=1);

namespace System\Routing;

use Contracts\Routing\RouteMiddleware as Contract;
use System\Collections\Sequential;

class RouteMiddleware extends Sequential implements Contract
{ }
