<?php
/* EXTERNAL RESOURCES */
require_once '../unirest-php/src/unirest.php';
// All classes & managers
spl_autoload_register(function($class) {
    if(strpos($class, "Manager") !== false)
        include __DIR__ . "../manager/" . $class . ".php";
    else
        include __DIR__ . "../class/" . $class . ".php";
});

class SuggestionManagerOld
{
    public static function isExistingSuggestion($suggestion) {
        return DBManager::isExistingRecord('suggestions', 'word', $suggestion);
    }

    public static function getClicksOf($suggestion) {
        return DBManager::getFieldValue('suggestions', 'clicks', 'word',$suggestion);
    }

    public static function getAllSuggestions() {
        return DBManager::selectAll('SELECT * FROM suggestions', []);
    }

    public static function getSuggestions($langWebsite, $search) {
        if ($langWebsite == 'en') {
            return self::getWordList($search);
        } else if ($langWebsite == 'nl') {
            $translatedWord = @file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20190702T100837Z.54ebaca40a431057.041c6f0fdd9a0f60684236f098fce4272e0e12d4&text=' . $search . '&lang=en');
            if($translatedWord) {
                $translatedWord = json_decode($translatedWord, true);
                //$langDetected = explode('-', $translatedWord['lang'])[0];
                $translatedWord = $translatedWord['text'][0];
                return self::getWordList($translatedWord);

                // Check
                /*$translatedWord2 = @file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20190702T100837Z.54ebaca40a431057.041c6f0fdd9a0f60684236f098fce4272e0e12d4&text=' . $search . '&lang=nl-en');
                $translatedWord2 = json_decode($translatedWord2, true);
                $translatedWord2 = $translatedWord2['text'][0];

                if($langDetected == 'en' && $search == $translatedWord && $search == $translatedWord2) {
                    $globalList = getWordList($search);
                } else if($langDetected == 'nl' || $search != $translatedWord2) {
                    /*$wordList = getWordList($translatedWord2);
                    $sentence = '';
                    $nb = count($wordList);
                    $i = 0;
                    foreach ($wordList as $word) {
                        if($i < $nb-1) {
                            $sentence .= $word->word . '%20,';
                        } else {
                            $sentence .= $word->word;
                        }
                        $i++;
                    }
                    $translatedList = translate('en', 'nl', $sentence);

                    $globalList = array_unique($translatedList);
                }*/
            }
        } else {
            return [];
        }
    }

    public static function translate($langSrc, $langDest, $sentence) {
        $sentence = str_replace(' ', '%20', $sentence);

        $translatedWord = @file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20190702T100837Z.54ebaca40a431057.041c6f0fdd9a0f60684236f098fce4272e0e12d4&text=' . $sentence . '&lang=' . $langSrc . '-' . $langDest);

        if($translatedWord) {
            $translatedWord = json_decode($translatedWord, true);
            $translatedWord = explode(',', $translatedWord['text'][0]);
            return $translatedWord;
        }
        return [];
    }

    public static function getWordList($wordSearched) {
        if(strlen($wordSearched) > 3 && strpos($wordSearched, 'the ') !== false) {
            $wordSearched = str_replace('the ', '', $wordSearched);
        }
        $wordSearched = str_replace(' ', '%20', $wordSearched);

        Unirest\Request::verifyPeer(false);
        $response = Unirest\Request::get("https://wordsapiv1.p.mashape.com/words/" . $wordSearched,
            array(
                "X-Mashape-Key" => "bdb41202abmsh5139c825d58c189p17ecd6jsne83d87c15e6a",
                "Accept" => "application/json"
            )
        );

        $wordList = [];
        $words = json_decode($response->raw_body, true);
        if(array_key_exists('results', $words)) {
            $words = $words['results'][0];
            if(array_key_exists('synonyms', $words)) {
                foreach ($words['synonyms'] as $word) {
                    array_push($wordList, $word);
                }
            }
            if(array_key_exists('typeOf', $words)) {
                foreach ($words['typeOf'] as $word) {
                    array_push($wordList, $word);
                }
            }
            if(array_key_exists('hasTypes', $words)) {
                foreach ($words['hasTypes'] as $word) {
                    array_push($wordList, $word);
                }
            }
        }

        $list = [];
        if(count($wordList) > 0) {
            $suggestionsDB = SuggestionManager::getAllSuggestions();

            $wordList = array_unique($wordList);
            foreach ($wordList as $word) {
                $found = false;
                $i = 0;
                $suggestion = new Suggestion($word, 0);
                while($i < count($suggestionsDB) && !$found) {
                    if(strcasecmp($word, $suggestionsDB[$i]['word']) == 0) {
                        $found = true;
                        $suggestion->clicks = $suggestionsDB[$i]['clicks'];
                    }
                    $i++;
                }
                array_push($list, $suggestion);
            }

            function my_sort_function($a, $b)
            {
                return $a->clicks < $b->clicks;
            }
            usort($list, 'my_sort_function');

            return $list;
        }

        return $list;
    }
}
