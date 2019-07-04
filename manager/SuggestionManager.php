<?php

    // All classes & managers
    spl_autoload_register(function($class) {
        if(strpos($class, "Manager") !== false)
            include __DIR__ . "../manager/" . $class . ".php";
        else
            include __DIR__ . "../class/" . $class . ".php";
    });

    class SuggestionManager
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
    }
