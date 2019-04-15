<?php

namespace Db2Cloud\Core;

use http\Exception\BadQueryStringException;
use League\Flysystem\Exception;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Doctrine\DBAL\ConnectionException;

class backupmysql{
    protected $host;

    protected $username;

    protected $password;

    protected $database;

    protected $path;

    public function __construct($host,$username,$password,$database,$path)
    {
        $this->host=$host;
        $this->username=$username;
        $this->password=$password;
        $this->database=$database;
        $this->path=$path;
    }

    /**
     *  Connect Function is used to check either connection is working or not
     *  @return bool
     */

    protected function connect(){
       try{
           // Create connection
           $conn = mysqli_connect($this->host, $this->username, $this->password);


       }catch(\Exception $e){
            throw new InvalidArgumentException("unable to connect to database");
       }
        return true;
    }



    public function backup(){
        if($this->connect()){
            exec("C:/xampp/mysql/bin/mysqldump.exe --user=$this->username --password=$this->password --host=$this->password $this->database >$this->path",$output, $return);
            return true;
        }else{
           return false;
        }

    }


}