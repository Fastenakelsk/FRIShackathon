<?php
    // All classes & managers
    spl_autoload_register(function($class) {
        if(strpos($class, "Manager") !== false)
            include __DIR__ . "../../manager/" . $class . ".php";
        else
            include __DIR__ . "../../class/" . $class . ".php";
    });
    ob_start();
    session_start();

    if(isset($_POST['suggestion']) && !empty($_POST['suggestion'])) {
        $suggestion = htmlspecialchars($_POST['suggestion']);
        if(DBManager::isExistingRecord('suggestions', 'word', $suggestion)) {
            DBManager::update('UPDATE suggestions SET clicks = clicks + 1 WHERE word = :word', ['word' => $suggestion]);
        } else {

            DBManager::insert('INSERT INTO suggestions(word, clicks) VALUES(:word, :clicks)', ['word' => $suggestion, 'clicks' => 1]);
        }
        $_SESSION['suggestion'] = $suggestion;
    }