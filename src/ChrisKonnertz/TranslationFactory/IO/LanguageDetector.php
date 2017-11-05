<?php

namespace ChrisKonnertz\TranslationFactory\IO;

// Note: We cannot use the contracts Illuminate\Contracts\Filesystem\Filesystem here,
// they do not contain all the methods that we expect.
use ChrisKonnertz\TranslationFactory\TranslationFactory;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;

class LanguageDetector implements LanguageDetectorInterface
{

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Config
     */
    protected $config;

    /**
     * LanguageDetector constructor.
     *
     * @param Filesystem $filesystem   An instance of Laravel's filesystem class
     * @param Config     $config       An instance of Laravel's config class
     */
    public function __construct(Filesystem $filesystem, Config $config)
    {
        $this->filesystem = $filesystem;
        $this->config = $config;
    }

    /**
     * Finds all language directories and based on them return the names of all available languages
     *
     * @return string[]
     */
    public function detect() : array
    {
        $mainDir = app()->langPath();
        $additionalDirs = $this->config->get(TranslationFactory::CONFIG_NAME.'.additional_paths');

        $dirs = array_merge([$mainDir], $additionalDirs);

        $languages = [];
        foreach ($dirs as $dir) {
              /** @var \Symfony\Component\Finder\SplFileInfo[] $files */
             $subDirs = $this->filesystem->directories($dir);

             foreach ($subDirs as $subDir) {
                 $pos = mb_strlen($dir);
                 $name = mb_strtolower(substr($subDir, $pos + 1));

                 if (! in_array($name, $languages)) {
                     $languages[] = $name;
                 }
             }

        }

        return $languages;
    }

}