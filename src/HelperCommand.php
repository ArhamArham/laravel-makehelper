<?php

namespace Arham\MakeHelper;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HelperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:helper {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $this->createBladeViews()
            ->createJsViews()
            ->mapJsComponents()
            ->createLivewireComponent()
            ->createMCFM();
    }

    private function createBladeViews()
    {
        if (!config("makehelper.blade_views")) {
            return $this;
        }

        return $this->creatingViewHelper(true);
    }

    private function createJsViews()
    {
        if (!config("makehelper.js_views")) {
            return $this;
        }

        return $this->creatingViewHelper();
    }

    private function mapJsComponents()
    {
        if (!config("makehelper.map_js_component")) {
            return $this;
        }

        $this->info('mapping js components into file');
        file_put_contents(
            resource_path(config("makehelper.paths.map_js_component")),
            PHP_EOL . $this->compileChunkStub(),
            FILE_APPEND
        );

        return $this;
    }

    private function createLivewireComponent()
    {
        if (!config("makehelper.livewire_component")) {
            return $this;
        }

        $componentName = $this->argument('name') . 'List';
        Artisan::call("livewire:make $componentName");
        $this->info('creating livewire component');
        return $this;
    }

    public function createMCFM()
    {
        if (!config("makehelper.mcfm")) {
            return $this;
        }

        $this->info('creating controller');
        Artisan::call("make:controller Admin/{$this->argument('name')} -r");
        $this->info('creating model with factory and migration');
        Artisan::call("make:model {$this->argument('name')} -fm");
    }

    private function compileViewsStub($file)
    {
        return str_replace(
            [
                '{{namespace}}',
                '{{vue}}',
                '{{data}}'
            ],
            [
                $this->camelCaseToString(),
                Str::kebab($this->argument('name')),
                Str::snake($this->argument('name'))
            ],
            file_get_contents($file)
        );
    }

    public function compileJsStub($file)
    {
        return str_replace(
            [
                '{{namespace}}',
                '{{data}}'
            ],
            [
                $this->camelCaseToString(),
                Str::snake($this->argument('name'))
            ],
            file_get_contents($file)
        );
    }

    public function compileChunkStub()
    {
        return str_replace(
            [
                '{{namespace}}',
                '{{name}}',
                '{{vue}}',
            ],
            [
                $this->argument('name'),
                Str::kebab($this->argument('name')),
                Str::snake($this->argument('name'))
            ],
            file_get_contents(__DIR__ . '/stubs/chunk.stub')
        );
    }

    private function camelCaseToString()
    {
        $pieces = preg_split('/(?=[A-Z])/', $this->argument('name'));
        return ucwords(implode(" ", $pieces));
    }

    private function creatingViewHelper($forBlade = false)
    {
        $this->info($forBlade ? 'creating blade view' : 'creating js views');

        $ext = $forBlade ? '.blade.php' : '.vue';
        $stubs = $forBlade ? '/stubs/views' : '/stubs/js';

        $path = Str::snake($this->argument('name'));
        $DIR = $forBlade ?
            config("makehelper.paths.blade_views") . $path :
            config("makehelper.paths.js_views") . $path;

        $directory = resource_path($DIR);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }


        $files = array_slice(scandir(__DIR__ . $stubs), 2);

        foreach ($files as $file) {
            $newFile = $directory . '/' . str_replace('.stub', $ext, $file);

            File::copy(__DIR__ . " {$stubs}/{$file}", $newFile);
            file_put_contents(
                $newFile,
                $forBlade ?
                    $this->compileViewsStub($newFile) :
                    $this->compileJsStub($newFile)
            );
        }

        return $this;
    }

}
