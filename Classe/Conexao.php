
<?php

class Db {
    public static function conexao(){
        try {
            $conexao = new PDO("mysql:host=localhost;dbname=heman;", "root", "");
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conexao;
        } catch (PDOException $e) {
            return NULL;
        }
    }
}

?>
