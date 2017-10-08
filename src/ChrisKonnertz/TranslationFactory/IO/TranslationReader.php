<?php

namespace ChrisKonnertz\TranslationFactory\IO;

// Note: We cannot use the contracts Illuminate\Contracts\Filesystem\Filesystem and
// Illuminate\Contracts\Translation\Translator here, they do not contain all the methods that we expect.
use ChrisKonnertz\TranslationFactory\TranslationBag;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;

class TranslationReader implements TranslationReaderInterface
{

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * TranslationReader constructor.
     *
     * @param Translator $translator
     * @param Filesystem $filesystem
     */
    public function __construct(Translator $translator, Filesystem $filesystem)
    {
        $this->translator = $translator;
        $this->filesystem = $filesystem;
    }

    /**
     * Reads all translations and returns them wrapped in a TranslationBag
     *
     * @return TranslationBag[]
     */
    public function readAll() : array
    {
        $path = app()->langPath();

        $baseLanguage = 'en'; // TODO change
        $translationBags = $this->loadPath($path, $baseLanguage);

        return $translationBags;
    }

    /**
     * Loads and returns translations from all files in a given directory and its subdirectories.
     *
     * @param string $basePath
     * @param        $baseLanguage
     * @return array|TranslationBag[]
     * @throws \Exception
     */
    protected function loadPath(string $basePath, string $baseLanguage) : array
    {
        // TODO this is just a first implementation, later on it might make sense to change it
        $basePath .= DIRECTORY_SEPARATOR.$baseLanguage;

        /** @var \Symfony\Component\Finder\SplFileInfo[] $files */
        $files = $this->filesystem->allFiles($basePath);

        $translationBags = [];

        foreach ($files as $file) {
            $content = $this->filesystem->getRequire($file->getPathname());

            if (! is_array($content)) {
                throw new \Exception('Translation file "'.$file->getPathname().'" does not contain an array');
            }

            $translationBags[] = new TranslationBag($content, $basePath.DIRECTORY_SEPARATOR, $file->getPathname());
        }

        return $translationBags;
    }

}