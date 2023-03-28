<?php

namespace App\Http\Actions\Web\Faq;

use App\Http\Actions\AbstractAction;
use Contracts\Configuration\Configuration;
use Contracts\Http\{Request, Response};
use Contracts\IO\FileSystem;

class Action extends AbstractAction
{

    private $config;

    private $filesystem;


    public function __construct(Configuration $config, FileSystem $filesystem, Responder $responder)
    {
        $this->config = $config;
        $this->filesystem = $filesystem;
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        $data = $this->config->get('pages.faq');
        $data['lastModified'] = $this->filesystem->getLastModified($data['path']);

        return $this->responder->handle($data);
    }
}
