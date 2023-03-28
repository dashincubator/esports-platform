<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Base Application Boot Configuration
 *
 *  Different Environments Require Additional Configuration, The Following
 *  Configuration Provides A Base That Should Be Extended
 *
 *  @see '/bootstrap/http.php'
 *
 */

$app = [];
$debug = true;
$paths = require 'paths.php';

/**
 *------------------------------------------------------------------------------
 *
 *  Application Container Bindings
 *
 */

$app['bindings'] = [
    Contracts\Bootstrap\Application::class => App\Bootstrap\Application::class,

    Contracts\Cache\Cache::class => System\Cache\Redis::class,
    Contracts\Cache\File::class => System\Cache\File::class,
    Contracts\Cache\Redis::class => System\Cache\Redis::class,

    Contracts\Calculator\Calculator::class => System\Calculator\Calculator::class,

    Contracts\Collections\Associative::class => System\Collections\Associative::class,
    Contracts\Collections\Factory::class => System\Collections\Factory::class,
    Contracts\Collections\Queue::class => System\Collections\Queue::class,
    Contracts\Collections\Sequential::class => System\Collections\Sequential::class,
    Contracts\Collections\Stack::class => System\Collections\Stack::class,

    Contracts\Configuration\Configuration::class => System\Configuration\Configuration::class,

    Contracts\Container\Container::class => System\Container\Container::class,
    Contracts\Container\ContextualBindingBuilder::class => System\Container\ContextualBindingBuilder::class,

    Contracts\Database\Connection::class => System\Database\Adapters\Pdo\Connection::class,
    Contracts\Database\Driver::class => System\Database\Adapters\Pdo\Mysql\Driver::class,
    Contracts\Database\Server::class => System\Database\Server::class,
    Contracts\Database\Statement::class => System\Database\Adapters\Pdo\Statement::class,

    Contracts\Debug\Debug::class => System\Debug\Debug::class,
    Contracts\Debug\Formatter::class => System\Debug\Formatter::class,
    Contracts\Debug\Logger::class => System\Debug\Logger::class,
    Contracts\Debug\Renderer::class => System\Debug\Renderer::class,

    Contracts\Encryption\Encrypter::class => System\Encryption\SodiumEncrypter::class,

    Contracts\Environment\Environment::class => System\Environment\Environment::class,
    Contracts\Environment\Parser::class => System\Environment\Parser::class,

    Contracts\Hashing\Hasher::class => System\Hashing\BcryptHasher::class,

    Contracts\Http\Factory::class => System\Http\Factory::class,
    Contracts\Http\Request::class => System\Http\Request::class,
    Contracts\Http\RequestFile::class => System\Http\RequestFile::class,
    Contracts\Http\RequestHeaders::class => System\Http\RequestHeaders::class,
    Contracts\Http\Response::class => System\Http\Response::class,
    Contracts\Http\ResponseCollection::class => System\Http\ResponseCollection::class,
    Contracts\Http\ResponseCookie::class => System\Http\ResponseCookie::class,
    Contracts\Http\ResponseHeaders::class => System\Http\ResponseHeaders::class,

    Contracts\IO\FileSystem::class => System\IO\FileSystem::class,

    Contracts\Jobs\Factory::class => System\Jobs\Factory::class,
    Contracts\Jobs\Job::class => System\Jobs\Job::class,
    Contracts\Jobs\Log::class => System\Jobs\Log::class,
    Contracts\Jobs\Queue::class => System\Jobs\Queue::class,
    Contracts\Jobs\Worker::class => System\Jobs\Worker::class,

    Contracts\Mail\Factory::class => System\Mail\Factory::class,
    Contracts\Mail\Mailer::class => System\Mail\Mailer::class,
    Contracts\Mail\Mail::class => System\Mail\Mail::class,

    Contracts\MultiCurl\MultiCurl::class => System\MultiCurl\MultiCurl::class,
    Contracts\MultiCurl\Parser\Json::class => System\MultiCurl\Parser\Json::class,
    Contracts\MultiCurl\Parser\Xml::class => System\MultiCurl\Parser\Xml::class,

    Contracts\Paypal\IPN::class => System\Paypal\IPN::class,

    Contracts\Pipeline\Pipeline::class => System\Pipeline\Pipeline::class,

    Contracts\QueryBuilder\Driver::class => System\QueryBuilder\Driver\MysqlDriver::class,
    Contracts\QueryBuilder\QueryBuilder::class => System\QueryBuilder\QueryBuilder::class,
    Contracts\QueryBuilder\Query\Delete::class => System\QueryBuilder\Query\Delete::class,
    Contracts\QueryBuilder\Query\Insert::class => System\QueryBuilder\Query\Insert::class,
    Contracts\QueryBuilder\Query\Query::class => System\QueryBuilder\Query\Query::class,
    Contracts\QueryBuilder\Query\Select::class => System\QueryBuilder\Query\Select::class,
    Contracts\QueryBuilder\Query\Update::class => System\QueryBuilder\Query\Update::class,

    Contracts\Redis\Redis::class => System\Redis\Redis::class,
    Contracts\Redis\Manager::class => System\Redis\Manager::class,

    Contracts\Routing\Factory::class => System\Routing\Factory::class,
    Contracts\Routing\Matcher::class => System\Routing\Matcher::class,
    Contracts\Routing\Route::class => System\Routing\Route::class,
    Contracts\Routing\RouteCollection::class => System\Routing\RouteCollection::class,
    Contracts\Routing\RouteCompiler::class => System\Routing\RouteCompiler::class,
    Contracts\Routing\RouteGroupOptions::class => System\Routing\RouteGroupOptions::class,
    Contracts\Routing\RouteMiddleware::class => System\Routing\RouteMiddleware::class,
    Contracts\Routing\RouteParser::class => System\Routing\RouteParser::class,
    Contracts\Routing\Router::class => System\Routing\Router::class,

    Contracts\Session\Session::class => System\Session\Session::class,

    Contracts\Slug\Generator::class => System\Slug\Generator::class,

    Contracts\Time\Time::class => System\Time\Time::class,

    Contracts\Upload\Image::class => System\Upload\Image::class,
    Contracts\Upload\Png::class => System\Upload\Png::class,

    Contracts\UUID\RandomGenerator::class => System\UUID\RandomGenerator::class,
    Contracts\UUID\UniqueGenerator::class => System\UUID\UniqueGenerator::class,

    Contracts\Validation\MessageTemplates::class => System\Validation\MessageTemplates::class,
    Contracts\Validation\Rule::class => System\Validation\Rule::class,
    Contracts\Validation\RulesParser::class => System\Validation\RulesParser::class,
    Contracts\Validation\Rules::class => System\Validation\Rules::class,
    Contracts\Validation\Validator::class => System\Validation\Validator::class,

    Contracts\View\Buffer::class => System\View\Buffer::class,
    Contracts\View\Engine::class => System\View\Engine::class,
    Contracts\View\Factory::class => System\View\Factory::class,
    Contracts\View\Loader::class => System\View\Loader::class,
    Contracts\View\View::class => System\View\View::class,
    Contracts\View\Extensions\Data::class => System\View\Extensions\Data::class,
    Contracts\View\Extensions\Extensions::class => System\View\Extensions\Extensions::class,
    Contracts\View\Extensions\Extension::class => System\View\Extensions\Extension::class,
    Contracts\View\Extensions\Render::class => System\View\Extensions\Render::class,
    Contracts\View\Extensions\Section::class => System\View\Extensions\Section::class,
    Contracts\View\Extensions\Template::class => System\View\Extensions\Template::class
];

/**
 *------------------------------------------------------------------------------
 *
 *  Directories To Include In Configuration Collection
 *
 *  Once Loaded The Directory Is Converted To A Dot Notation Key And Applied
 *  As A Prefix To The Files Within The Directory.
 *  - Replacing Directory Separator With '.'
 *
 *  Example:
 *  'something/else' => 'something.else'
 *  'something/else/filename.php' => 'something.else.filename'
 *
 */

$app['config'] = [
    '', // Root Directory

    'api',
    'contracts',

    'pages',
    'pages/game',
    'pages/ladder',
    'pages/warzone',
    'view/extensions'
];

/**
 *------------------------------------------------------------------------------
 *
 *  True If Application Is In Debug Mode, Otherwise False
 *
 */

$app['debug'] = $debug;

/**
 *------------------------------------------------------------------------------
 *
 *  Exception Handler Configuration
 *
 */

$app['exceptions'] = [
    'errorlog' => "{$paths['logs']}/error.log",

    'development' => [
        'html' => "{$paths['resources']['views']}/pages/error/500/html/development.php",
        'json' => "{$paths['resources']['views']}/pages/error/500/json/development.php"
    ],
    'production' => [
        'html' => "{$paths['resources']['views']}/pages/error/500/html/production.php",
        'json' => "{$paths['resources']['views']}/pages/error/500/json/production.php"
    ]
];

/**
 *------------------------------------------------------------------------------
 *
 *  Default Application Host
 *
 *  Replaced With HTTP Request Host
 *
 */

$app['host'] = 'gamrs.net';

/**
 *------------------------------------------------------------------------------
 *
 *  Default Application Name
 *
 */

$app['name'] = 'gamrs';

/**
 *------------------------------------------------------------------------------
 *
 *  Autoloader Namespaces
 *
 */

$app['namespaces'] = [
    'App\\' => $paths['app'],
    'Contracts\\' => $paths['contracts'],
    'System\\' => $paths['src']
];

/**
 *------------------------------------------------------------------------------
 *
 *  Application Service Providers
 *
 */

use App\Bootstrap\Providers;

$app['providers'] = [
    Providers\Commands\ImageProvider::class,
    Providers\Commands\LadderProvider::class,
    Providers\Commands\UserProvider::class,

    Providers\Contracts\DatabaseProvider::class,
    Providers\Contracts\QueryBuilderProvider::class,
    Providers\Contracts\MailProvider::class,
    Providers\Contracts\RedisProvider::class,
    Providers\Contracts\RoutingProvider::class,
    Providers\Contracts\SessionProvider::class,
    Providers\Contracts\SquareProvider::class,
    Providers\Contracts\SlugProvider::class,
    Providers\Contracts\ValidationProvider::class,
    Providers\Contracts\ViewProvider::class,

    Providers\DataSource\LadderProvider::class,
    Providers\DataSource\UserProvider::class,

    Providers\Services\Api\ModernWarfareProvider::class,

    Providers\View\Extensions\AnchorProvider::class,
    Providers\View\Extensions\DrawerProvider::class,
    Providers\View\Extensions\LeaderboardProvider::class,
    Providers\View\Extensions\ModalProvider::class,
    Providers\View\Extensions\SvgProvider::class,
    Providers\View\Extensions\TimeProvider::class,
    Providers\View\Extensions\UploadProvider::class,

    Providers\FlashProvider::class,
    Providers\OrganizationProvider::class
];

/**
 *------------------------------------------------------------------------------
 *
 *  Application Boot Stages
 *
 */

use App\Bootstrap\Stages;

$app['stages'] = [
    Stages\HandleExceptions::class,
    Stages\RegisterEnvironmentBinding::class,
    Stages\LoadConfiguration::class,
    Stages\RegisterProviders::class,
    Stages\BootProviders::class
];

/**
 *------------------------------------------------------------------------------
 *
 *  Application Timezone
 *
 */

$app['timezone'] = 'America/Los_Angeles';

/**
 *------------------------------------------------------------------------------
 *
 *  Return Base Application Boot Configuration
 *
 */

return $app;
