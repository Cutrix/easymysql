<?php

declare (strict_types=1);

/**
 * author: Cutrix ^_^
 * email: houessinonlandry@gmail.com
 * l'objetif de cette classe est de permettre d'accelerer la manipulation de la bdd.
 */

class easymysql {
    
    private $db;


    public function __construct($db) {
        $this->setDb($db);
    }
        
    
    public function setDb(PDO $db) {
        $this->db = $db;
    }
    
    /**
     * Selectionne de donnees de la base de donnees sans options
     * @param string $table-Nom de la table
     * @param int $fetch-Retourne le mode de fonctionnement
     * @param string-variadic
     * @return mixed
     */
    
    public function getFromMysql(string $table, int $fetch, string ...$arg) {
        if (empty($arg)) {
            $q = $this->db->prepare("SELECT * FROM $table");
            $q->execute();
            return $q->fetchAll($fetch);
        }
        $q = $this->db->prepare('SELECT '.$this->walk($arg).' FROM '.$table);
        $q->execute();
        return $q->fetchAll($fetch);
    }
    
    /**
     * Permet d'obtenir des valeurs avec des options
     * @param string $table
     * @param int $fetch
     * @param array $options
     * @param array $values
     * @param string $arg
     * @return array
     */
    
    public function getFromMysqlOptions(string $table, int $fetch, array $options, array $values, string ...$arg) {
        if (empty($arg)) {            
            $q = $this->db->prepare('SELECT * FROM '.$table.' WHERE '.$this->walk($options, "=?, ").'=?');
            $q->execute(array($this->walk($values)));
            return $q->fetch($fetch);
        }
        $q = $this->db->prepare('SELECT '.$this->walk($arg).' FROM '.$table.' WHERE '.$this->walk($options, "=?, ").'=?');
        $q->execute(array($this->walk($values)));
        return $q->fetch($fetch);
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
        //'INSERT INTO '.$table.' ('.$this->walk($options, ", ").') VALUES ('.$this->walk($values, ", ").')'
        //echo 'INSERT INTO '.$table.' ('.$this->walk($options, ", ").') VALUES ('."'".$this->walk($values, "','")."')";
        
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
    
    private function walk(array $tb, $delimiter = " ") {
        if (empty($delimiter)) return implode(', ', $tb);
        return implode($delimiter, $tb);
    }
     
}
