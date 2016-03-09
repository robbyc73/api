<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 17/02/16
 * Time: 3:36 PM
 */

namespace ApiServerBundle\Model;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\Common\Util\Debug;



class ApiServerModel {

    private $db;
    function __construct($db) {
       $this->db = $db;
    }


    private function checkError($result,$errorInfo)
    {
        if($result === false) $result['error'] = $errorInfo;

        return $result;
    }

    public function getJobBoardInfo($cid,$jid) {
        $errorInfo = 'Job Board not found, please check your client ID and JobBoard ID combination are correct and are active';
        $result = false;

        try {
            $stmt = $this->db->prepare('SELECT "ID", "ClientID", "Name", "URL" FROM "JobBoard"
                                        JOIN client_job_board ON("JobBoard"."ID" = brsjobboardid AND "JobBoard"."ClientID" = brsclientid)
                                        WHERE "ID" = :jid AND "ClientID" = :cid');
            $stmt->bindValue("jid",$jid);
            $stmt->bindValue("cid",$cid);
            $stmt->execute();
            $result = $stmt->fetch();
        }
        catch(DriverException $e) {
            $errorInfo = Debug::dump($e);
        }

        return $this->checkError($result,$errorInfo);
    }

    public function getCategoryOptions($cid,$jid) {

        $stmt = $this->db->prepare('SELECT "Table2RowID", "CategoryID", "Name"
          		      FROM "TableRow_TableRow"
		              JOIN "CategoryOption" ON "CategoryOption"."ID" = "Table2RowID"
		              WHERE "Table_TableID" = 118
		              AND "Table1RowID" = :cid
		              ORDER BY "CategoryID", "Name"');

        $stmt->bindValue("cid",$cid);
        $stmt->execute();
        $result = $stmt->fetch();

        return $this->checkError($result,'Categories not found, please check your client and job board id are correct');

    }



}