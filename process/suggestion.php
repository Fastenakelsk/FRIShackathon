<?php
    require_once '../manager/DBManager.php';

    if(isset($_POST['suggestion']) && !empty($_POST['suggestion'])) {
        $suggestion = htmlspecialchars($_POST['suggestion']);
        if(DBManager::isExistingRecord('suggestions', 'word', $suggestion)) {
            DBManager::update('UPDATE suggestions SET clicks = clicks + 1 WHERE word = :word', ['word' => $suggestion]);
        } else {
            DBManager::insert('INSERT INTO suggestions(word, clicks) VALUES(:word, :clicks)', ['word' => $suggestion, 'clicks' => 1]);
        }
    }
