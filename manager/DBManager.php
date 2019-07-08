<?php
    class DBManager {

        /* METHODS */
        public static function connect() {
            try {
                return new PDO("mysql:host=68.183.212.195;dbname=fris;charset=utf8mb4",'fris','X@utAiCS*FtT79t@T@Da8bt9', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            }
            catch (Exception $e) {
                die("Connexion au serveur MySQL impossible : " . $e->getMessage());
            }
        }

        public static function select($request, $parameters) {
            if(strpos($request, "SELECT") !== false) {
                $res = self::connect()->prepare($request);
                $check = $res->execute($parameters);
                if($check)
                    return $res->rowCount() ? $res->fetch(PDO::FETCH_ASSOC) : false;
                else
                    return false;
            }
            else
                return false;
        }

        public static function selectAll($request, $parameters) {
            if(strpos($request, "SELECT") !== false) {
                $res = self::connect()->prepare($request);
                $check = $res->execute($parameters);
                if($check)
                    return $res->rowCount() ? $res->fetchAll(PDO::FETCH_ASSOC) : false;
                else
                    return false;
            }
            else
                return false;
        }

        public static function insert($request, $parameters) {
            if (strpos($request, "INSERT") !== false)
                return self::connect()->prepare($request)->execute($parameters) ? true : false;
            else
                return false;
        }

        public static function update($request, $parameters) {
            if(strpos($request, "UPDATE") !== false)
                return self::connect()->prepare($request)->execute($parameters) ? true : false;
            else
                return false;
        }

        public static function delete($request, $parameters) {
            if(strpos($request, "DELETE") !== false)
                return self::connect()->prepare($request)->execute($parameters) ? true : false;
            else
                return false;
        }

        public static function nbRecords($table) {
            $nbRecords = self::connect()->prepare("SELECT COUNT(*) FROM $table");
            return $nbRecords->execute();
        }

        public static function isExistingRecord($table, $field, $value) {
            $nbRecords = self::connect()->prepare("SELECT * FROM $table WHERE $field =:value");
            $nbRecords->execute(["value" => $value]);
            if($nbRecords->rowCount() > 0)
                return true;
            return false;
        }

        public static function getFieldValue($table, $fieldSearch, $field, $value) {
            return self::select("SELECT $fieldSearch FROM $table WHERE $field =:value", ["value" => $value]);
        }
    }
