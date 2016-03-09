<?php
# oauth-server/src/ApiServerBundle/Entity/Client.php


namespace ApiServerBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;

/**
 * @ORM\Entity
 */
class ClientJobBoard extends BaseClient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */

    protected $brsclientid;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    protected $brsjobboardid;

    /**
     * @return mixed
     */
    public function getBrsclientid()
    {
        return $this->brsclientid;
    }

    /**
     * @param mixed $brsclientid
     */
    public function setBrsclientid($brsclientid)
    {
        $this->brsclientid = $brsclientid;
    }

    /**
     * @return mixed
     */
    public function getBrsjobboardid()
    {
        return $this->brsjobboardid;
    }

    /**
     * @param mixed $brsjobboardid
     */
    public function setBrsjobboardid($brsjobboardid)
    {
        $this->brsjobboardid = $brsjobboardid;
    }





}