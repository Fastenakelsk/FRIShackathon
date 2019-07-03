<?php
    /*if(isset($_POST['search']) && !empty($_POST['search']) && isset($_POST['lang']) && !empty($_POST['lang'])) {
        $search = str_replace(' ', '%20', htmlspecialchars($_POST['search']));
        $langWebsite = htmlspecialchars($_POST['lang']);
        if($langWebsite == 'en') {
            getSynonyms($langWebsite, $search);
        }
        else if($langWebsite == 'nl') {
            $translation = @file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20190702T100837Z.54ebaca40a431057.041c6f0fdd9a0f60684236f098fce4272e0e12d4&text=' . $search . '&lang=en');
            if($translation) {
                $translation = json_decode($translation, true);
                $langDetected = explode('-', $translation['lang'])[0];
                if($langDetected == 'nl') {
                    getSynonyms($langDetected, $translation['text'][0]);
                }
                else if($langDetected == 'en') {
                    getSynonyms($langDetected, $search);
                }
            }
            else {
                echo "An error has occurred.";
            }
        }
        else {
            echo "An error has occurred.";
        }
    }

    function getSynonyms($language, $word) {
        $synonyms = @file_get_contents('http://thesaurus.altervista.org/thesaurus/v1?word=' . $word . '&language=en_US&output=json&key=1XAZ0WBuqhZIfhDy8k1r&callback=process');
        if($synonyms) {
            $synonyms = substr($synonyms, 8);
            $synonyms = substr_replace($synonyms, "", -1);
            $synonyms = json_decode($synonyms, true);

            foreach ($synonyms['response'] as $list) {
                $newList = explode('|', $list['list']['synonyms']);
                if($language == 'en') {
                    foreach ($newList as $synonym) {
                        if(strpos($synonym, '(') != false) {
                            $syn = substr($synonym,0,strrpos($synonym,'('));
                        }
                        else {
                            $syn = $synonym;
                        }
                        echo $syn . '<br>';
                    }
                }
                else if($language == 'nl') {
                    $synonymsString = "";
                    foreach ($newList as $synonym) {
                        if (strpos($synonym, '(') != false) {
                            $syn = substr($synonym, 0, strrpos($synonym, '('));
                        } else {
                            $syn = $synonym;
                        }
                        $synonymsString .= $syn . ',';
                    }
                    $synonymsString = str_replace(' ', '%20', htmlspecialchars($synonymsString));
                    $translations = file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20190702T100837Z.54ebaca40a431057.041c6f0fdd9a0f60684236f098fce4272e0e12d4&text=' . $synonymsString . '&lang=en');
                    $translations = json_decode($translations, true);
                    $translations = explode(',', $translations['text'][0]);
                    foreach ($translations as $translation) {
                        echo $translation . '<br>';
                    }
                }
            }
        }
        else {
            echo 'An error has occurred.';
        }
    }*/

    require_once './unirest-php/src/unirest.php';

    $search = "banana";
    Unirest\Request::verifyPeer(false);
    $response = Unirest\Request::get("https://wordsapiv1.p.mashape.com/words/" . $search,
        array(
            "X-Mashape-Key" => "bdb41202abmsh5139c825d58c189p17ecd6jsne83d87c15e6a",
            "Accept" => "application/json"
        )
    );

    $wordList = [];
    $words = json_decode($response->raw_body, true);
    foreach ($words["results"][0]['synonyms'] as $word) {
        array_push($wordList, $word);
    }
    foreach ($words["results"][0]['typeOf'] as $word) {
        array_push($wordList, $word);
    }
    foreach ($words["results"][0]['hasTypes'] as $word) {
        array_push($wordList, $word);
    }
    foreach ($wordList as $word) {
        echo $word . '<br>';
    }
