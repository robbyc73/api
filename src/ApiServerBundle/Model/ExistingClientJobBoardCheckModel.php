<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 22/02/16
 * Time: 12:41 PM
 */

namespace ApiServerBundle\Model;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\Common\Util\Debug;
use ApiServerBundle\Model\Model;





class ExistingClientJobBoardCheckModel {

    private $db;
    function __construct($db) {
        $this->db = $db;
    }

    public function getExistingOAuthClientJobBoard($cid,$jid) {

        try {
            $stmt = $this->db->prepare('SELECT id FROM client_job_board WHERE brsclientid = :cid AND brsjobboardid = :jid');
            $stmt->bindValue("jid",$jid);
            $stmt->bindValue("cid",$cid);
            $stmt->execute();
            $result = $stmt->fetch();
        }
        catch(DriverException $e) {
            $result = Debug::dump($e);
        }

        return $result;

    }

    public function getBrsClientAndJobBoard($cid,$jid)
    {

        try {
            $stmt = $this->db->prepare('SELECT "ID", "ClientID" FROM "JobBoard" WHERE "ID" = :jid AND "ClientID" = :cid');
            $stmt->bindValue("jid",$jid);
            $stmt->bindValue("cid",$cid);
            $stmt->execute();
            $result = $stmt->fetch();
        }
        catch(DriverException $e) {
            $errorInfo = Debug::dump($e);
        }
    }

}