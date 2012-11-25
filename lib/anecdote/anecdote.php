<?php
namespace lib\anecdote;

require_once('lib/db/database.php');

use lib\db\Database;
use PDO;

class Anecdote {
    private $id = 0;
    private $username;
    private $anecdote;
    private $date;
    private $time;
    
    public function __construct(){
    }
    private function hydrate(array $datas){
        $this->id = $datas['id'];
        $this->username = $datas['username'];
        $this->anecdote = $datas['anecdote'];
        $this->date = $datas['date'];
        $this->time = $datas['time'];
    }
    public function getId() {
        return $this->id;
    }
    public function getUsername() {
        return $this->username;
    }
    public function setUsername($username) {
        $this->username = (string) $username;
    }
    public function getAnecdote() {
        return $this->anecdote;
    }
    public function setAnecdote($anecdote) {
        $this->anecdote = (string) $anecdote;
    }
    public function getDate() {
        return $this->date;
    }
    public function getTime() {
        return $this->time;
    }
    public function toArray() {
        return array('username'=>$this->getUsername(),
            'anecdote'=>$this->getAnecdote(),
            'date'=>$this->getDate(),
            'time'=>$this->getTime());
    }

    static public function countAnecdote(){
        $req = DataBase::getInstance()->prepare('SELECT COUNT(id) FROM nuitinfo');
        $req->execute();
        $count = $req->fetchColumn();
        $req->closeCursor();
        return $count;
    }
    
    static public function saveAnecdote(Anecdote $anecdote){ 
        $req = DataBase::getInstance()->prepare('INSERT INTO anecdotes (username, anecdote) VALUES (:username, :anecdote)');
        $req->bindValue('username', $anecdote->getUsername(), PDO::PARAM_STR);
        $req->bindValue('anecdote', $anecdote->getAnecdote(), PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
    }
    static public function getBDDAnecdotes(){
        $anecdotes = array();
        $req = DataBase::getInstance()->prepare('SELECT id, username, anecdote, DATE_FORMAT(timestamp, \'%c/%e/%x\') AS date, DATE_FORMAT(timestamp, \'%Hh%i\') AS time FROM anecdotes');
        $req->execute();
        while($datas = $req->fetch()){
            $anecdote = new Anecdote();
            $anecdote->hydrate($datas);
            $anecdotes[] = $anecdote;
        }
        $req->closeCursor();
        return $anecdotes;
    }
    static public function getBDDAnecdote($id){
        $req = DataBase::getInstance()->prepare('SELECT id, username, anecdote, DATE_FORMAT(timestamp, \'%c/%e/%x\') AS date, DATE_FORMAT(timestamp, \'%Hh%i\') AS time FROM anecdotes WHERE id=:id');
        $req->bindvalue('id', $id, PDO::PARAM_INT);
        $req->execute();
        $datas = $req->fetch();
        $anecdote = new Anecdote();
        $anecdote->hydrate($datas);
        $req->closeCursor();
        return $anecdote;
    }
}

?>
