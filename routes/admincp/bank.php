<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

// $r->group(
//     $r->middleware(function(RouteMiddleware $m) {
//           $m->push(Middleware\Auth\Admincp\ManagesBankWithdraws::class);
//       })
//       ->name('bank.')
//       ->pattern('/bank'),
//     function (Router $r) {
//         $r->get('withdraws', '/withdraws', Web\Admincp\Bank\Withdraws\Action::class);
//
//         $r->group(
//             $r->name('withdraw.'),
//             function (Router $r) {
//                 $r->post('process.command', '/process', Commands\User\Bank\Withdraw\Process\Action::class);
//             }
//         );
//     }
// );
