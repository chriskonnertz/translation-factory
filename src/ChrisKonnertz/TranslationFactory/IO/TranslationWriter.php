<?php

namespace ChrisKonnertz\TranslationFactory\IO;

// Note: We cannot use the contract Illuminate\Contracts\Filesystem\Filesystem here,
// it does not contain all the methods that we expect.
use ChrisKonnertz\TranslationFactory\TranslationBag;
use Illuminate\Filesystem\Filesystem;

class TranslationWriter implements TranslationWriterInterface
{

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * TranslationWriter constructor.
     *
     * @param Filesystem $filesystem An instance of Laravel's filesystem class
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Writes the translations to translation files
     *
     * @param TranslationBag $translationBag  The translation bag with the translation
     * @param string         $customOutputDir If not an empty string, use this path to store the files
     * @throws \Exception
     */
    public function write(TranslationBag $translationBag, $customOutputDir = '')
    {
        foreach ($translationBag->getTranslations() as $language => $translations) {
            if ($language === $translationBag->getBaseLanguage()) {
                continue;
            }

            // Note: We do not use $translationBag->getName() so we are independent from that method
            $filename = basename($translationBag->getBaseFile());
            $rootDir = $customOutputDir ?: $translationBag->getSourceDir();
            $fileDir = $rootDir.$language.DIRECTORY_SEPARATOR;

            $content = "<?php".PHP_EOL.PHP_EOL.'return '.$this->arrayToCode($translations).';';

            if (! $this->filesystem->exists($fileDir)) {
                $success = $this->filesystem->makeDirectory($fileDir, 0755, true);
                if ($success === false) {
                    throw new \Exception('Error: Could not create directory "'.$fileDir.'"');
                }
            }

            $success = $this->filesystem->put($fileDir.$filename, $content);
            if ($success === false) {
                throw new \Exception('Error: Could not write to file "'.$filename.'"');
            }
        }
    }

    /**
     * This methods expects an array as parameter. It will return a piece of PHP code.
     * This code will consist of the array as PHP code.
     *
     * @param array $array
     * @return string
     */
    protected function arrayToCode(array $array)
    {
        return var_export($array, true);
    }

}