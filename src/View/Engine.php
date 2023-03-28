<?php declare(strict_types=1);

namespace System\View;

use Closure;
use Contracts\View\{Engine as Contract, Factory, Loader, View};
use Contracts\View\Extensions\{Data, Extensions, Section};
use Contracts\Support\Arrayable;
use Exception;

class Engine implements Contract
{

    private const FILE_EXTENSION = '.php';

    private const PATH_PREFIX = '@';


    // View Extension Collection
    private $extensions;

    // View Component Factory
    private $factory;

    // Template Loader
    private $loader;

    // Registered Path Aliases
    private $paths = [];

    // Used To Keep Track Of Parents
    private $rendering = [];


    public function __construct(Extensions $extensions, Factory $factory, Loader $loader)
    {
        $this->extensions = $extensions;
        $this->factory = $factory;
        $this->loader = $loader;
    }


    public function addExtension(string $key, string $classname) : void
    {
        $this->extensions->add($key, $classname);
    }


    public function addPath(string $key, string $path) : void
    {
        $this->paths[self::PATH_PREFIX . $key] = $path;
    }


    private function buildData(Section $section, array $data) : array
    {
        return [
            'app'     => $this->extensions,
            'data'    => $this->factory->createData($data),
            'include' => $this->factory->createRender($data),
            'layout'  => $this->factory->createTemplate(),
            'section' => $section
        ];
    }


    private function buildPath(View $root, string $path) : string
    {
        // Prepend Parent Directory Paths To Catch Sibling Includes, etc.
        $i = 0;

        while(mb_substr($path, 0, mb_strlen(self::PATH_PREFIX)) !== self::PATH_PREFIX) {
            if (file_exists($path . self::FILE_EXTENSION) || ($parent ?? null) === $root) {
                break;
            }

            $parent = $this->rendering[count($this->rendering) - ++$i] ?? $root;
            $path = $parent->getDirectory() . '/' . $path;
        }

        // Replace Route Path Directives
        while (mb_strpos($path, self::PATH_PREFIX) !== false) {
            $path = str_replace(array_keys($this->paths), array_values($this->paths), $path, $count);

            // Nothing Was Replaced We Are Done Or We Have A Misconfigured Path Directive
            if ($count === 0) {
                break;
            }
        }

        // Find Direct Path
        $file = realpath($path . self::FILE_EXTENSION);

        if (!$file) {
            throw new Exception("View Engine Cannot Render A Template That Does Not Exist! '{$path}'");
        }

        return $file;
    }


    private function loop(View $root, Section $section, View $current) : string
    {
        if ($current->getPath() === '') {
            return $section('content');
        }

        // Build Required Information
        $data = $this->buildData($section, array_replace($root->getData(), $current->getData()));
        $path = $this->buildPath($root, $current->getPath());

        // Start Rendering - Update Stack
        $this->rendering[] = $current;

        // Rendering
        $section->start('content');
            echo $this->loader->load($path, $data);
        $section->end();

        // Traverse Render Tree - Render Parent Templates, etc.
        $output = $this->loop($root, $section, $data['layout']);

        // End Rendering - Update Stack
        array_pop($this->rendering);

        return $output;
    }


    public function render(View $view) : string
    {
        return $this->loop($view, $this->factory->createSection(), $view);
    }
}
