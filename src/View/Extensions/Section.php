<?php declare(strict_types=1);

namespace System\View\Extensions;

use Contracts\View\Buffer;
use Contracts\View\Extensions\Section as Contract;

class Section implements Contract
{

    // Output Buffer
    private $buffer;

    // Map Of Section Key To Action
    private $actions = [];

    // Completed Sections
    private $sections = [];


    public function __construct(Buffer $buffer)
    {
        $this->buffer = $buffer;
    }


    public function __invoke(string $key) : string
    {
        return $this->sections[$key] ?? '';
    }


    private function action(string $action, string $key) : void
    {
        $this->actions[$key] = $action;

        $this->buffer->start($key);
    }


    public function end() : void
    {
        $buffer = $this->buffer->end();

        $action = $this->actions[$buffer['key']] ?? '';
        $content = $this->sections[$buffer['key']] ?? '';

        if ($action === 'push') {
            $content .= $buffer['content'];
        }
        elseif ($action === 'prepend') {
            $content = $buffer['content'] . $content;
        }
        elseif ($action === 'start') {
            $content = $buffer['content'];
        }

        $this->sections[$buffer['key']] = $content;
    }


    public function has(string $key) : bool
    {
        return isset($this->sections[$key]);
    }


    public function prepend(string $key) : void
    {
        $this->action('prepend', $key);
    }


    public function push(string $key) : void
    {
        $this->action('push', $key);
    }


    public function start(string $key) : void
    {
        $this->action('start', $key);
    }
}
