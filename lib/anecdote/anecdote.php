<?php
namespace lib\anecdote;
require('lib/db/database.php');


use lib\db\Database;
use PDO;

class Anecdote {
    private $id = 0;
    private $username;
    private $anecdote;


    public function __construct(){
    }
    private function hydrate(array $datas){
     	$this->id = $datas['id'];
     	$this->username = $datas['username'];
     	$this->anecdote = $datas['anecdote'];
    }
    public function getId(){
     	return $this->id;
    }
    public function getUsername(){
     	return $this->username;
    }
    public function getTheAnecdote(){
    	return $this->anecdote;
    }
    static public function getAnecdote($id){
        $req = DataBase::getInstance()->prepare('SELECT id, username, anecdote, DATE_FORMAT(timestamp, \'%c/%e/%x\') AS date, DATE_FORMAT(timestamp, \'%Hh%i\') AS time FROM anecdotes WHERE id = :id');
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