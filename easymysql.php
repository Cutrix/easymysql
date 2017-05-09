<?php

declare (strict_types=1);

/**
 * author: Cutrix ^_^
 * email: houessinonlandry@gmail.com
 * l'objetif de cette classe est de permettre d'accelerer la manipulation de la bdd.
 * Elle permet de manipuler les donnees de base (crud) 
 */

class easymysql {
    
    private $db;
    const EVERYTHING_SELECTOR = "SELECT * FROM ";    


    public function __construct($db) {
        $this->setDb($db);
    }               
    
    /**
     * Selectionne de donnees de la base de donnees sans options
     * @param string $table de la table
     * @param int $fetch Retourne le mode de fonctionnement
     * @param string $arg variadic
     * @return mixed
     */
    
    public function getFromMysql(string $table, int $fetch = 0, string ...$arg) {
        if (empty($arg)) {
            $q = $this->db->prepare(self::EVERYTHING_SELECTOR.$table);
            $q->execute();
            return $q->fetchAll($fetch);
        }
        $q = $this->db->prepare('SELECT '.$this->walk($arg).' FROM '.$table);
        $q->execute();
        return $q->fetchAll($fetch);
    }
    
    /**
     * Permet de selectionner des donnees de la bdd de maniere unique(sans doublon)
     * @param string $table
     * @param int $fetch
     * @param string $arg
     * @return array
     */
    
    public function getFromMysqlUnique(string $table, int $fetch = 0, string ...$arg) {
        if (empty($arg)) {
            $q = $this->db->prepare("SELECT DISTINCT * FROM $table");
            $q->execute();
            return $q->fetchAll($fetch);
        }
        $q = $this->db->prepare('SELECT DISTINCT '.$this->walk($arg).' FROM '.$table);
        $q->execute();
        return $q->fetchAll($fetch);
    }
    
    /**
     * Permet d'obtenir des valeurs avec des options, le parametre fetch est defini 
     * avec 0 par defaut on peut aussi utiliser les notations PDO (PDO::FETCH_OBJ) par 
     * @param string $table
     * @param int $fetch
     * @param array $options
     * @param array $values
     * @param string $arg
     * @return array
     */
    
    public function getFromMysqlOptions(string $table, int $fetch, array $options, array $values, string ...$arg) {
        if (empty($arg)) {            
            $q = $this->db->prepare(self::EVERYTHING_SELECTOR.$table.' WHERE '.$this->walk($options, "=?, ").'=?');
            $q->execute(array($this->walk($values)));
            return $q->fetchAll($fetch);
        }
        $q = $this->db->prepare('SELECT '.$this->walk($arg).' FROM '.$table.' WHERE '.$this->walk($options, "=?, ").'=?');
        $q->execute(array($this->walk($values)));
        return $q->fetchAll($fetch);
    }
    
    /**
     * Defini les valeurs avec optiions dans la bdd (sans doublon)
     * @param string $table
     * @param int $fetch
     * @param array $options
     * @param array $values
     * @param string $arg
     * @return array
     */
    
    public function getFromMysqlOptionsUnique(string $table, int $fetch, array $options, array $values, string ...$arg) {
        if (empty($arg)) {            
            $q = $this->db->prepare('SELECT DISTINCT * FROM '.$table.' WHERE '.$this->walk($options, "=?, ").'=?');
            $q->execute(array($this->walk($values)));
            return $q->fetchAll($fetch);
        }
        $q = $this->db->prepare('SELECT DISTINCT '.$this->walk($arg).' FROM '.$table.' WHERE '.$this->walk($options, "=?, ").'=?');
        $q->execute(array($this->walk($values)));
        return $q->fetchAll($fetch);
    }
    
    /**
     * Permet d'ajouter des donnees a la base de donnees
     * @param string $table
     * @param array $options
     * @param array $values
     * @return mixed
     */
    
    public function addToMysql(string $table, array $options, array $values) {
        $q = $this->db->prepare('INSERT INTO '.$table.' ('.$this->walk($options, ", ").') VALUES ('."'".$this->walk($values, "','")."')");
        $q->execute();             
        return true;        
    }
    
    /**
     * Permet de modifier des donnees de la bdd
     * @param string $table
     * @param string $amodif
     * @param string $nvval
     * @param string $modif
     * @param string $oldVal
     */
    
    
    public function updateFromMysql(string $table, string $amodif, string $nvval, string $modif, string $oldVal) {
        $q = $this->db->prepare('UPDATE '.$table.' SET '.$amodif.' = "'.$nvval.'" WHERE '.$modif.' = "'.$oldVal.'"');
        $q->execute();       
    }
    
    /**
     * Ajout d'une methode permettant de faire un recherche avec des regex
     * Peut permettre aussi de chercher avec des REGEX
     * @param string $table la table sur laquelle se fait la recherche
     * @param string $q Description
     */
    
    public function searchFromMysql(string $table, $search, int $fetch = 0, string $column_id = 'id', string ...$arg) {
        if (empty($arg)) {
            if (gettype($search) == 'integer') {
                $q = $this->db->prepare(self::EVERYTHING_SELECTOR.$table." WHERE ".$column_id." = ".$search);
                $q->execute();
                return $q->fetchAll($fetch);
            }
        }
    }
    
    
    /**
     * Supprime des donnees de la base de donnees
     * @param string $table
     * @param string $option
     * @param string $value
     */
    
    public function deleteFromMysql(string $table, string $option, string $value) {
        $q = $this->db->prepare("DELETE FROM ".$table." WHERE ".$option." = '".$value."'");        
        $q->execute();
        return true;
    }  
    
    
    public function query($req, $fetch = 0) {
        $q = $this->db->prepare($req);
        $q->execute();
        return $q->fetchAll($fetch);
    }


    /**
     * countFromMysql permet de compter des donnees de la base
     * @param string nom de la table
     * @return mixed        
     * */

    public function countFromMysql(string $table)
    {
        return $this->db->query("SELECT COUNT(*) FROM $table")->fetchColumn();
    }
    
    private function walk(array $tb, $delimiter = " ") {
        if (empty($delimiter)) return implode(', ', $tb);
        return implode($delimiter, $tb);
    }
    
    private function setDb(PDO $db) {
        $this->db = $db;
    }
     
}
