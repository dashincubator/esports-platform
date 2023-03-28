<?php

namespace App\Http\Actions\Web\Error\Log;

use App\Http\Actions\AbstractAction;
use Contracts\Configuration\Configuration;
use Contracts\Http\{Request, Response};
use Contracts\IO\FileSystem;

class Action extends AbstractAction
{

    private $filesystem;

    private $file;


    public function __construct(Configuration $config, FileSystem $filesystem, Responder $responder)
    {
        $this->file = $config->get('app.exceptions.errorlog');
        $this->filesystem = $filesystem;
        $this->responder = $responder;
    }


    public function delete(Request $request) : Response
    {
        $this->filesystem->deleteFile($this->file);

        return $this->view($request);
    }


    public function view(Request $request, int $limit = 10) : Response
    {
        $errors = [];

        if ($this->filesystem->isReadable($this->file)) {
            $errors = explode(PHP_EOL, $this->filesystem->read($this->file));
            $errors = array_reverse($errors);
            $errors = array_slice($errors, 0, $limit);

            $errors = array_map(function($line) {
                return json_decode($line, true);
            }, $errors);

            $errors = array_filter($errors);

            if (count($errors)) {
                $errors = array_merge(...$errors);
            }
        }

        return $this->responder->handle(compact('errors'));
    }
}
