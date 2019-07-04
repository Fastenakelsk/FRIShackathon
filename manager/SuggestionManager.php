<?php

require_once '../manager/DBManager.php';

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
