<?php

namespace App\Http\Actions\Web\Ladder\Faq;

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
        $faq = [];
        $ladder = $request->getAttributes()->get('ladder');
        $lastModified = $this->filesystem->getLastModified($this->config->get('pages.faq.path'));

        foreach ($this->config->get('pages.faq.categories') as $section) {
            if (!in_array(strtolower($section['title']), [
                'ladders', 'leagues', 'rating system', 'roster lock',
                'support center', 'team management'
            ])) {
                continue;
            }

            $faq[] = $section;
        }

        return $this->responder->handle(compact('faq', 'ladder', 'lastModified'));
    }
}
