<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\Service\ServerNotification;


class WebSocketServerCommand extends Command
{

    private $notification;

    public function __construct(ServerNotification $notification)
    {
        $this->notification = $notification;
        parent::__construct();
    }



    protected function configure()
    {
        $this->setName('app:web-socket:server')->setDescription('Start a web socket server')
            ->setHelp('This command help us to start & stop web socket notification server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server = IoServer::factory(new HttpServer(
            new WsServer(
                $this->notification
            )
        ), 8080);
        $server->run();
        $output->writeln('Command Successfully run.....');
    }
}//@