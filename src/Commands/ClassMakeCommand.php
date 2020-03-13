<?php
/**
 * Author: Danny Villa Kalonji
 * Date: 13/03/2019
 * Time: 14:36
 */

namespace Davinet\ArtisanCommand\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ClassMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:class {filename}
                            {--kind=class : This option accept either "class"(default) or "trait" or "interface" value.}
                            {--separator=\\ : Character used to separate file and its parent(s) folder(s).}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new class or trait file';

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
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->isCorrectFilename($this->argument('filename'))) {
            $separator = $this->getSeparator();
            $kind = $this->getKind();

            if ($separator !== null && $kind !== null) {
                $path = base_path(str_replace($separator, '/', $this->argument('filename')) . '.php');

                if ($this->replaceExistingFile($path, 'There is already a file with this name do you want to replace it ? [y/n]')) {
                    $filename = explode($separator, $this->argument('filename'));

                    $this->createFoldersIfNecessary($filename);
                    $stub = $this->getStub($kind);
                    $stub = $this->replaceKindName($kind, $filename[count($filename) - 1], $stub);
                    $namespace = '';
                    for ($i = 0; $i < count($filename) - 1; $i++)
                        $namespace .= ucfirst($filename[$i]).'\\';

                    $stub = $this->replaceNamespace(Str::replaceLast('\\', '', $namespace), $stub);

                    file_put_contents($path, $stub);

                    $this->info(ucfirst($kind).' created successfully');
                }
            }
        } else
            $this->error('The filename is not correct.');
    }

    /**
     * Replace every Dummy[Kind] with the right [kind] name.
     *
     * @param $name
     * @param $stub
     * @return mixed
     */
    protected function replaceKindName($kind, $name, $stub)
    {
        return str_replace('Dummy'.ucfirst($kind), ucfirst($name), $stub);
    }

    /**
     * Set the right namespace in the stub.
     *
     * @param $namespace
     * @param $stub
     * @return mixed
     */
    protected function replaceNamespace($namespace, $stub)
    {
        if (!empty($namespace))
            return str_replace('DummyNamespace', 'namespace '.$namespace.';', $stub);
        return str_replace('DummyNamespace', '', $stub);
    }

    /**
     * Create a set of folders if necessary.
     *
     * @param $filename
     * @return void
     */
    protected function createFoldersIfNecessary($filename)
    {
        $folder = base_path('');
        for ($i = 0; $i < count($filename) - 1; $i++) {
            if (!is_dir($folder . '/' . $filename[$i])) {
                mkdir($folder . '/' . $filename[$i]);
            }
            $folder .= '/' . $filename[$i];
        }
    }

    /**
     * Check if the filename is correct.
     *
     * @param $name
     * @return bool
     */
    protected function isCorrectFilename($name)
    {
        return preg_match('#^[a-zA-Z][\a-zA-Z0-9\._]+$#', $name);
    }


    /**
     * Check if the filename exists and if it could be replaced.
     *
     * @param $filename
     * @param $question
     * @return bool
     */
    protected function replaceExistingFile($filename, $question)
    {
        $replaceExistingFile = true;
        if (file_exists($filename)) {
            do {
                $input = $this->ask($question);
            } while (strtolower($input) != 'y' && strtolower($input) != 'n');

            if (strtolower($input) == 'n')
                $replaceExistingFile = false;
        }
        return $replaceExistingFile;
    }

    /**
     * Retrieve a stub for a kind of class.
     *
     * @param $kind
     * @return bool|string
     */
    protected function getStub($kind)
    {
        return file_get_contents(__DIR__.'/stubs/'.$kind.'.stub');
    }

    /**
     * Get the kind option's value.
     *
     * @return array|null|string
     */
    protected function getKind()
    {
        if ($this->option('kind') !== null) {
            if (preg_match('#^class|trait|interface$#', $this->option('kind')))
                return $this->option('kind');
            else {
                $this->error('This is kind value is unexpected. The kind may be either "class" or "trait" or "interface".');
                return null;
            }
        }

        return 'class';
    }

    /**
     * Get the separator from the option.
     *
     * @return array|string|null
     */
    protected function getSeparator()
    {
        if ($this->option('separator') !== null) {
            if (mb_strlen($this->option('separator')) > 1) {
                $this->error('This is an invalid separator. Please choose a separator between "." and "\" characters.');
                return null;
            } else
                return $this->option('separator');
        }

        return '\\';
    }
}
