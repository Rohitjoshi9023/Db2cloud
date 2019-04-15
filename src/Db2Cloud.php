<?php
namespace Db2Cloud;
use Db2Cloud\Core\backupmysql;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class Db2Cloud{


     /**
     *  FUNCTION IS USED TO CREATE BACKUP OF VARIOUS DATABASE
     *
     * @return bool
     */
     public function backup(){

         if(config('backupconfig.connection_type')=="mysql"){
             self::mysqlBackup();
         }
       return true;
     }

    /**
     * FUNCTION IS USED TO CREATE MYSQL DATABASE BACKUP
     *
     * @return bool
     */
     public function mysqlBackup(){
         // CHECK MYSQL CONNECTION DETAIL
         $detail=self::getMysqlConnectionDetial();

         // SAVE LOG TO LOG FILE
         self::saveLogIfAllowed("Initializing Backup on server at ".date("d-M-Y H:i:s",time()));

         $backup=new backupmysql($detail["host"],$detail["username"],$detail["password"],$detail["database"],__DIR__."/db_file.sql");
         $response=$backup->backup();

         // SAVE LOG TO LOG FILE
         if($response){
            self::saveLogIfAllowed(" Backup completed on server at  ".date("d-M-Y H:i:s",time()));
         }else{
             self::saveLogIfAllowed(" Backup failed on server at  ".date("d-M-Y H:i:s",time()));
         }

        return $response;
     }

    /**
     * FUNCTION IS USED TO CREATE GET MYSQL CONNECTION DETAIL FROM CONFIG FILE
     *
     * @return bool
     */
     protected function getMysqlConnectionDetial(){
        return config('database.connections.mysql');
     }

    /**
     * FUNCTION IS USED TO UPLOAD DATABASE BACKUP FILE TO VARIOUS LOCATION
     *
     * @return bool
     */
     public function uploadDB(){
        $type=explode(',',config('backupconfig.storage_type'));
         if(in_array("local",$type)){
           return self::storeLocal();
        }
         if(in_array("s3",$type)){
           return self::storeS3();
        }
         if(in_array("gdrive",$type)){
             return self::storeGDrive();
         }

     }

    /**
     * FUNCTION IS USED TO UPLOAD DATABASE BACKUP FILE IN LOCAL SERVER
     *
     * @return bool
     */
     protected function storeLocal(){
       self::saveLogIfAllowed("Moving file to local ".config('backupconfig.prefix_name')." at ".date("d-M-Y H:i:s",time()));
       $destination=config('backupconfig.storage_configs.local.path').config('backupconfig.prefix_name').date("d_M_Y")."_".time().".sql";
       return Storage::disk('s3')->put($destination,file_get_contents(__DIR__.'/db_file.sql'));
     }

    /**
     * FUNCTION IS USED TO UPLOAD DATABASE BACKUP FILE TO S3
     *
     * @return bool
     */
     protected function storeS3(){
         // SET A FILENAME
         $filename=self::getFileName();

          // Save Log
          self::saveLogIfAllowed("Uploading backup file($filename) on S3 at ".date("d-M-Y H:i:s",time()));

          // Set a destination path
          $destination=config('backupconfig.storage_configs.S3.path').$filename;

          // Save to S3
          $response=Storage::disk('s3')->put($destination,file_get_contents(__DIR__.'/db_file.sql'));

          if($response){
              self::saveLogIfAllowed("Backup Completed of file($filename) on s3 at  ".date("d-M-Y H:i:s",time()));

          }else{
              self::saveLogIfAllowed("Backup Failed of file($filename) on s3 at  ".date("d-M-Y H:i:s",time()));
          }

       return $response;
     }
    /**
     * FUNCTION IS USED TO UPLOAD DATABASE BACKUP FILE TO S3
     *
     * @return bool
     */
    protected function storeGDrive(){
        $destination=config('backupconfig.prefix_name').date("d_M_Y")."_".time().".sql";
        return Storage::disk('gdrive')->put($destination,file_get_contents(__DIR__.'/db_file.sql'));
    }

    protected function saveLogIfAllowed($message){
        if(config('backupconfig.log')==true){
            Log::info($message);
        }

    }
    protected function getFileName(){
        return config('backupconfig.prefix_name').date("d_M_Y")."_".time().".sql";
    }
    public function removeDBFile(){
        self::saveLogIfAllowed("Removing backup file from local at ".date("d-M-Y H:i:s",time()));
      return @unlink(__DIR__.'/db_file.sql');
    }

}