<?php

namespace Davinet\ArtisanCommand\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Repository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name} {--model= : The model on which the repository class will be based on}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieve the stub content from the repository's stub file.
     *
     * @return mixed
     */
    protected function getStub()
    {
        return file_get_contents(__DIR__.'/stubs/repository.stub');
    }

    /**
     * Retrieve the stub content from the repository's empty stub file.
     *
     * @return bool|string
     */
    protected function getEmptyStub()
    {
        return file_get_contents(__DIR__.'/stubs/empty.repository.stub');
    }

    /**
     * Replace every DummyClass with the right class name.
     *
     * @param $name
     * @param $stub
     * @return mixed
     */
    protected function replaceClassName($name, $stub)
    {
        $class = ucfirst($name);
        return str_replace('DummyClass', $class, $stub);
    }

    /**
     * Replace every DummyProperty with the right property name.
     *
     * @param $name
     * @param $stub
     * @return mixed
     */
    protected function replacePropertyName($name, $stub)
    {
        $property = lcfirst(Str::camel($name));
        return str_replace('DummyProperty', $property, $stub);
    }

    /**
     * Replace every DummyModel with the right model name.
     *
     * @param $name
     * @param $stub
     * @return mixed
     */
    protected function replaceModelName($name, $stub)
    {
        $model = ucfirst($name);
        return str_replace('DummyModel', $model, $stub);
    }

    /**
     * Replace the namespace of the namespace of the model.
     *
     * @param $namespace
     * @param $stub
     * @return mixed
     */
    protected function replaceModelNamespace($namespace, $stub)
    {
        return str_replace('DummyModelNamespace', ucfirst($namespace), $stub);
    }

    /**
     * Rewrite actually the content in the file.
     *
     * @param $filename
     * @param $content
     */
    protected function putInFile($filename, $content)
    {
        if (!is_dir(app_path('/Repositories')))
            mkdir(app_path('/Repositories'));
        file_put_contents($filename, $content);
    }

    /**
     * Set the right name and namespace.
     *
     * @param $model
     * @param $namespace
     * @return void
     */
    protected function setModelAndNamespace(&$model, &$namespace)
    {
        $exploded = str_contains($model, '/') ? explode('/', $model) : explode('\\', $model);
        $model = Arr::last($exploded);
        $namespace = '';

        for ($i = 0; $i < count($exploded) - 1; $i++)
            $namespace .= $exploded[$i].'\\';

        $namespace = Str::replaceLast('\\','', $namespace);
    }

    /**
     * Check if a model file exists.
     *
     * @param $model
     * @return bool
     */
    protected function modelFileExists($model)
    {
        return file_exists( base_path(lcfirst($model).'.php')) || file_exists( base_path(lcfirst(str_replace('\\', '/', $model)).'.php'));
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->option('model');
        $namespace = 'App';
        if (empty($name)) {
            $this->error('Please the name of the repository is expected.');
        } else {
            $content = null;

            if (is_null($model)) {
                $content = $this->replaceClassName($name, $this->getEmptyStub());
            } else {
                if (Str::contains($model, ['\\', '/'])) {
                    $this->setModelAndNamespace($model, $namespace);
                }

                if ($this->modelFileExists($namespace.'\\'.$model)) {
                    $content = $this->replaceModelNamespace($namespace, $this->getStub());
                    $content = $this->replaceModelName($model, $content);
                    $content = $this->replacePropertyName($model, $content);
                    $content = $this->replaceClassName($name, $content);
                } else {
                    $this->output->error('The specified model "'.$this->option('model').'" does not exist.');
                }
            }

            if (!is_null($content)) {
                $filename = app_path('Repositories/'.ucfirst($name).'.php');

                if (file_exists($filename)) {
                    do {
                        $input = $this->ask("There is a repository with this name ($name) do you want to replace it ? [o/n] ");
                    } while (strtolower($input) != 'o' && strtolower($input) != 'n');

                    if('o' == strtolower($input)){
                        $this->putInFile($filename, $content);
                        $this->info('Reporitory created successfully.');
                    }
                } else {
                    $this->putInFile($filename, $content);
                    $this->info('Reporitory created successfully.');
                }
            }
        }
    }
}
