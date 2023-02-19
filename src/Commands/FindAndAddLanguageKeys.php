<?php

namespace IMPelevin\PSPShared\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\Finder;

class FindAndAddLanguageKeys extends Command
{

    const STORAGE_PATH = 'lang';

    private array $locales;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'language:find-and-add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically find, translate and save missing translation keys.';

    public function __construct()
    {
        $this->locales = $this->getLocales();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $alreadyTranslated = $this->loadAllSavedTranslations();
        $translationsKeys = $this->findKeysInFiles();
        $this->saveNewKeys($translationsKeys, $alreadyTranslated);

        return Command::SUCCESS;
    }

    private function findKeysInFiles(): array
    {
        $path = [resource_path('ts'), resource_path('views')];
        $functions = ['\$t', 'trans', '@lang'];
        $pattern =
            "[^\w|>]" .                          // Must not have an alphanum or _ or > before real method
            "(" . implode('|', $functions) . ")" .  // Must start with one of the functions
            "\(" .                               // Match opening parentheses
            "[\'\"]" .                           // Match " or '
            "(" .                                // Start a new group to match:
            "([^\1)]+)+" .                       // this is the key/value
            ")" .                                // Close group
            "[\'\"]" .                           // Closing quote
            "[\),]";                            // Close parentheses or new parameter
        $finder = new Finder();
        $finder->in($path)->name(['*.vue', '*.php'])->files();
        $keys = [];
        foreach ($finder as $file) {
            if (preg_match_all("/$pattern/siU", $file->getContents(), $matches)) {
                foreach ($matches[2] as $key) {
                    $keys[$key] = '';
                }
            }
        }

        return $keys;
    }

    private function loadAllSavedTranslations(): array
    {
        $finder = new Finder();
        $finder->in(base_path(self::STORAGE_PATH))->name(['*.json'])->files();
        $translations = [];
        foreach ($finder as $file) {
            $locale = $file->getFilenameWithoutExtension();
            if (!in_array($locale, $this->locales)) {
                continue;
            }
            $this->info('loading: ' . $locale);
            $jsonString = $file->getContents();
            $translations[$locale] = json_decode($jsonString, true);
        }

        return $translations;
    }

    private function saveNewKeys(array $translationsKeys, array $alreadyTranslated)
    {
        foreach ($this->locales as $locale) {
            $currentTranslated = $alreadyTranslated[$locale] ?? [];

            $newKeysFound = array_diff_key($translationsKeys, $currentTranslated);

            if (count($newKeysFound) < 1) {
                continue;
            }

            $this->info(count($newKeysFound) . ' new keys found for "' . $locale . '"');
            $newKeysWithValues = $this->translateKeys($locale, $newKeysFound);
            $this->saveToFile($locale, $newKeysWithValues, $currentTranslated);
        }
    }

    private function translateKeys(string $locale, array $keys): array
    {
        foreach ($keys as $keyIndex => $keyValue) {
            $keys[$keyIndex] = $keyIndex;
        }

        return $keys;
    }

    private function saveToFile(string $locale, array $newKeysWithValues, array $alreadyTranslated)
    {
        $disk = Storage::build([
            'driver' => 'local',
            'root' => base_path(self::STORAGE_PATH),
        ]);

        $localeTranslations = array_merge($alreadyTranslated, $newKeysWithValues);
        $disk->put($locale . '.json', json_encode($localeTranslations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    private function getLocales()
    {
        $locales = array_keys(config('languages', []));
        return array_diff($locales, [config('app.locale')]);
    }
}
