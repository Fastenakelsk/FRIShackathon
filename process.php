<?php
    /* EXTERNAL RESOURCES */
    require_once './unirest-php/src/unirest.php';


    /* MAIN CODE */
    if(isset($_POST['search']) && !empty($_POST['search']) && isset($_POST['lang']) && !empty($_POST['lang'])) {
        $globalList = [];
        $search = str_replace(' ', '%20', htmlspecialchars($_POST['search']));
        $langWebsite = htmlspecialchars($_POST['lang']);

        if ($langWebsite == 'en') {
            $globalList = getWordList($search);
        } else if ($langWebsite == 'nl') {
            $translatedWord = @file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20190702T100837Z.54ebaca40a431057.041c6f0fdd9a0f60684236f098fce4272e0e12d4&text=' . $search . '&lang=en');
            if($translatedWord) {
                $translatedWord = json_decode($translatedWord, true);
                $langDetected = explode('-', $translatedWord['lang'])[0];
                $translatedWord = $translatedWord['text'][0];

                // Check
                $translatedWord2 = @file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20190702T100837Z.54ebaca40a431057.041c6f0fdd9a0f60684236f098fce4272e0e12d4&text=' . $search . '&lang=nl-en');
                $translatedWord2 = json_decode($translatedWord2, true);
                $translatedWord2 = $translatedWord2['text'][0];

                if($langDetected == 'en' && $search == $translatedWord && $search == $translatedWord2) {
                    $wordList = getWordList($search);
                    foreach ($wordList as $word) {
                        echo $word . '<br>';
                    }
                } else if($langDetected == 'nl' || $search != $translatedWord2) {
                    $wordList = getWordList($translatedWord2);
                    $sentence = '';
                    foreach ($wordList as $word) {
                        $sentence .= $word . ',';
                    }
                    $globalList = translate('en', $langDetected, $sentence);
                }
<<<<<<< Updated upstream
=======
                else if($langDetected == 'en') {
                    $globalList = getWordList($search);
                }
>>>>>>> Stashed changes
            }
        } else {
            echo "Invalid language.";
        }
        if(count($globalList) > 0){
            ?>
            <script>
                $('#synonymList').empty();
                $('#synonymList').append('<li id="chosenWord" class="list-group-item active"> <?= $search ?></li>\n');
            </script>
            <?php
            foreach ($globalList as $item) {
                ?>
                <script>
                    $('#synonymList').append('<li id="chosenWord" class="list-group-item"> <?= $item ?></li>\n');
                </script>
                <?php
            }
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
        return [];
    }

    function getWordList($wordSearched) {
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
        return $wordList;
    }
