<?php
namespace ApiServerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiServerBundle\Model\ApiServerModel;

/**
 * @Route("api")
 */
class ApiServerController extends Controller
{
    /**
     * @param Request $request
     *
     * @Method({"GET"})
     * @Route("/client/{cid}/jobboard/{jid}", requirements={"cid" = "\d+", "jid" = "\d+"}, defaults={"cid" = 1, "jid" = 1})
     *
     * @return JsonResponse
     */
    public function getJobBoardInfoAction($cid,$jid)
    {
        //check valid client for the token issued here
        if ($this->get('client_jobboard')->isValidClientJobBoard($cid,$jid))
        {
            $jobboard = new ApiServerModel($this->get('database_connection'));
            $output = $jobboard->getJobBoardInfo($cid, $jid);
        }
        else
        {
            $output = array('error' => 'invalid credentials. Please check your client and jobboard has been set up for api access');
        }

        return new JsonResponse($output);
    }

    /**
     * @param Request $request
     *
     * @Method({"POST"})
     * @Route("/jobboard")
     *
     * @return Response
     */
    public function createTeamAction(Request $request)
    {
        return new Response('POST your team to Server');
    }
}