<?php
    /* EXTERNAL RESOURCES */
    require_once './unirest-php/src/unirest.php';


    /* MAIN CODE */
    if(isset($_POST['search']) && !empty($_POST['search'])) {
        $search = str_replace(' ', '%20', htmlspecialchars($_POST['search']));
        $langWebsite = 'en';

        if ($langWebsite == 'en') {
            $wordList = getWordList($search);
            foreach ($wordList as $word) {
                echo $word . '<br>';
            }
        } else if ($langWebsite == 'nl') {
            $translatedWord = @file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20190702T100837Z.54ebaca40a431057.041c6f0fdd9a0f60684236f098fce4272e0e12d4&text=' . $search . '&lang=en');
            if($translatedWord) {
                $translatedWord = json_decode($translatedWord, true);
                $langDetected = explode('-', $translatedWord['lang'])[0];
                if($langDetected == 'nl') {
                    $wordList = getWordList($translatedWord['text'][0]);
                    $sentence = '';
                    foreach ($wordList as $word) {
                        $sentence .= $word . ',';
                    }
                    $translatedWordList = translate('en', $langDetected, $sentence);
                    foreach ($translatedWordList as $word) {
                        echo $word . '<br>';
                    }
                }
                else if($langDetected == 'en') {
                    $wordList = getWordList($search);
                    foreach ($wordList as $word) {
                        echo $word . '<br>';
                    }
                }
            }
        } else {
            echo "Invalid language.";
        }
    }


    /* FUNCTIONS */
    function translate($langSrc, $langDest, $sentence) {
        $sentence = str_replace(' ', '%20', $sentence);
        $translatedWord = @file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20190702T100837Z.54ebaca40a431057.041c6f0fdd9a0f60684236f098fce4272e0e12d4&text=' . $sentence . '&lang=' . $langSrc . '-' . $langDest);
        if($translatedWord) {
            $translatedWord = json_decode($translatedWord, true);
            $translatedWord = explode(',', $translatedWord['text'][0]);
            return $translatedWord;
        }
    }

    function getWordList($wordSearched) {
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
        return $wordList;
    }
