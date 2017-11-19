<?php

namespace ChrisKonnertz\TranslationFactory\IO;

// Note: We cannot use the contract Illuminate\Contracts\Filesystem\Filesystem here,
// it does not contain all the methods that we expect.
use ChrisKonnertz\TranslationFactory\TranslationBag;
use Illuminate\Filesystem\Filesystem;
use function PHPSTORM_META\type;

class TranslationWriter implements TranslationWriterInterface
{

    /**
     * Template for the translation file
     */
    const FILE_TEMPLATE = '<?php'.PHP_EOL.PHP_EOL.'return %values%;';

    /**
     * The number of spaces a tab consists of
     */
    const TAB_SIZE = 4;

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

            $arrayCode = $this->arrayToCode($translations);
            $content = str_replace('%values%', $arrayCode, self::FILE_TEMPLATE);

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
     * @param array $array The array that has to be written as PHP code
     * @param int   $level The current level in the array, starting at 1
     * @return string
     */
    protected function arrayToCode(array $array, $level = 1)
    {
        $code = '['.PHP_EOL;
        if ($level === 1) {
            $code .= PHP_EOL; // Add empty line
        }

        // Find out the length of the longest key (written key)
        $maxKeyCodeLength = -1;
        foreach ($array as $key => $item) {
            $keyCode = var_export($key, true);
            if (mb_strlen($keyCode) > $maxKeyCodeLength) {
                $maxKeyCodeLength = mb_strlen($keyCode);
            }
        }

        foreach ($array as $key => $item) {
            $keyCode = var_export($key, true);
            $code .= str_repeat(' ', $level * self::TAB_SIZE).$keyCode; // Add spaces for indentation
            $code .= str_repeat(' ', $maxKeyCodeLength - mb_strlen($keyCode)); // Add spaces for better formatting
            $code .= ' => ';

            if (is_array($item)) {
                $code .= $this->arrayToCode($item, $level + 1);
            } else {
                $code .= var_export($item, true);
            }

            $code .= ','.PHP_EOL;
        }


        if ($level === 1) {
            $code .= PHP_EOL; // Add empty line
        }
        $code .= str_repeat(' ', ($level - 1) * self::TAB_SIZE).']'; // Add spaces for indentation

        return $code;
    }

}