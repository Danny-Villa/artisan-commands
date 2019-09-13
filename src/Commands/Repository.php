<?php

namespace Davinet\ArtisanCommand\Commands;

use Illuminate\Console\Command;

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
        $property = lcfirst(camel_case($name));
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
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->option('model');

        if (empty($name)) {
            $this->error('Please the name of the repository is expected.');
        } else {
            if ((empty($model) || !isset($model)) || (!file_exists(app_path('/'.$model.'.php')))) {
                $content = $this->replaceClassName($name, $this->getEmptyStub());
            } else {
                $content = $this->replaceClassName($name, $this->getStub());
                $content = $this->replaceModelName($model, $content);
                $content = $this->replacePropertyName($model, $content);
            }

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