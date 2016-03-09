<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 27/02/16
 * Time: 10:46 AM
 */

namespace ApiServerBundle\Manager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\Common\Util\Debug;


class ClientJobBoardManager {

    private $em;
    private $access_token_manager;
    private $token_storage;

    public function __construct($db,$access_token_manager,$token_storage) {
        $this->db = $db;
        $this->access_token_manager = $access_token_manager;
        $this->token_storage = $token_storage;

    }

    public function isValidClientJobBoard($clientid,$jobboardid) {
        $valid = false;

        //retrieve the client via the token, and compare the cid/jid stored to the ones passed through
        $token        = $this->token_storage->getToken();
        $accessToken  = $this->access_token_manager->findTokenByToken($token->getToken());
        $client = $accessToken->getClient();

        if($client->getBrsclientid() == $clientid && $client->getBrsjobboardid() == $jobboardid)
        {
            $valid = true;
        }

        /*try {
            $stmt = $this->db->prepare('SELECT count(*) as "Count" FROM "JobBoard"
                                        JOIN client_job_board ON("JobBoard"."ID" = brsjobboardid AND "JobBoard"."ClientID" = brsclientid)
                                        WHERE "ID" = :jid AND "ClientID" = :cid AND "JobBoard"."Active"');
            $stmt->bindValue("jid",$jid);
            $stmt->bindValue("cid",$cid);
            $stmt->execute();
            $result = $stmt->fetch();

        }
        catch(DriverException $e) {
            $errorInfo = Debug::dump($e);
        }*/

        return $valid;
    }
}