<?php
/**
 * Author: Danny Villa Kalonji
 * Date: 07/11/2019
 * Time: 03:34
 */

namespace Davinet\ArtisanCommand\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Str;

class File extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:file {filename} {--ext= :The file extension. By default is php}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new file';

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
            $extension = $this->getExtension();
            $path = base_path(str_replace('.', '/', $this->argument('filename')).'.'.$extension);

            if ($this->replaceExistingFile($path, 'There is already a file with this name do you want to replace it ? [y/n]')) {
                $filename = explode('.', $this->argument('filename'));

                $this->createFoldersIfNecessary($filename);

                file_put_contents($path, '');
                $this->info('File created successfully');
            }
        } else
            $this->error('The filename is not correct.');
    }

    /**
     * Get the file extension specified by the option.
     * PHP is considered as the default extension.
     *
     * @return string
     */
    protected function getExtension()
    {
        if ($this->hasOption('ext') && $this->option('ext') !== null)
            if (Str::startsWith($this->option('ext'), '.'))
                return Str::replaceFirst('.', '', $this->option('ext'));
            else
                return $this->option('ext');
        return 'php';
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
        return (bool) preg_match('#^[a-zA-Z][a-zA-Z0-9._\-]+$#', $name);
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
}
