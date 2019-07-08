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


    /* MAIN CODE */
    if(isset($_POST['search']) && !empty($_POST['search']) && isset($_POST['lang']) && !empty($_POST['lang'])) {
        $search = str_replace(' ', '%20', htmlspecialchars($_POST['search']));
        $langWebsite = htmlspecialchars($_POST['lang']);

        $globalList = SuggestionManager::getSuggestions($langWebsite, $search);

        $_SESSION['search'] = $search;
        $_SESSION['suggestions'] = serialize($globalList);
        $_SESSION['lang'] = $langWebsite;
        header("Location: ../index.php");
    }
    header('Location: ../index.php');
