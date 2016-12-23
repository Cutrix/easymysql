<?php

declare (strict_types=1);

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
    
    public function addToMysql($table, array $options, array $values) {
        
    }
    
    public function deleteFromMysql($table, $option, $value) {
        $q = $this->db->prepare("DELETE FROM ".$table." WHERE ".$option." = '".$value."'");        
        $q->execute();
    }            
    
    private function walk(array $tb, $delimiter = " ") {
        if (empty($delimiter)) return implode(', ', $tb);
        return implode($delimiter, $tb);
    }
     
}
