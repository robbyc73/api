<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 16/02/16
 * Time: 9:13 AM
 */

namespace ApiServerBundle\Command;


use FOS\OAuthServerBundle\Entity\Client;
use FOS\OAuthServerBundle\Entity\ClientManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ApiServerBundle\Model\ExistingClientJobBoardCheckModel;

class CreateOauthClientCommand extends ContainerAwareCommand
{
    const REDIRECT_URI = 'redirect-uri';
    const GRANT_TYPE = 'grant-type';
    const BRS_CID = 'brs-clientid';
    const BRS_JID = 'brs-jobboardid';

   /* private $clientManager;

    public function __construct(ClientManager $clientManager)
    {
        parent::__construct();

        $this->clientManager = $clientManager;
    }*/

    protected function configure()
    {
        $this
            ->setName('oauth:client:create')
            ->setDescription('Creates a new OAuth client.')
            ->addOption(
                self::REDIRECT_URI,
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Sets redirect uri for client. Can be used multiple times.'
            )
            ->addOption(
                self::GRANT_TYPE,
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Sets allowed grant type for client. Can be used multiple times.'
            )
            ->addOption(
                self::BRS_CID,
                null,
                InputOption::VALUE_REQUIRED,
                'Sets the brs client ID, this is a required field, must be an integer'
            )
            ->addOption(
                self::BRS_JID,
                null,
                InputOption::VALUE_REQUIRED,
                'Sets the brs Jobboard ID, this is a required field, must be an integer'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');

        //check if the client and job board currently exist in db and added to the ClientJobBoard table
        $check = new ExistingClientJobBoardCheckModel($this->getContainer()->get("database_connection"));
        $result = $check->getExistingOAuthClientJobBoard($input->getOption(self::BRS_CID),$input->getOption(self::BRS_JID));

        if($result === false ) {


            $client = $clientManager->createClient();
            $client->setRedirectUris($input->getOption(self::REDIRECT_URI));
            $client->setAllowedGrantTypes($input->getOption(self::GRANT_TYPE));
            $client->setBrsclientid($input->getOption(self::BRS_CID));
            $client->setBrsjobboardid($input->getOption(self::BRS_JID));
            $clientManager->updateClient($client);

            $this->echoCredentials($output, $client);
        }
        else {
            $output->writeln('Failed to setup, client and job board combination already exist in the ClientJobBoard table...');
        }
    }

    private function echoCredentials(OutputInterface $output, Client $client)
    {
        $output->writeln('OAuth client has been created...');
        $output->writeln(sprintf('Public ID: %s', $client->getPublicId()));
        $output->writeln(sprintf('Secret ID: %s', $client->getSecret()));
        $output->writeln(sprintf('BRS Client ID: %s', $client->getBrsclientid()));
        $output->writeln(sprintf('BRS JobBoard ID: %s', $client->getBrsclientid()));

    }
}
