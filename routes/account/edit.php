<?php

use App\Http\Actions\Web;

$r->get('edit', '/edit', Web\Account\Edit\Action::class);
