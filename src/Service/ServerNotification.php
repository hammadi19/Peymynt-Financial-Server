<?php

namespace App\Service;


use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AppUser;


class ServerNotification implements MessageComponentInterface
{

    protected $connections = array();

    private $entityManager;

    protected $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager,EntityManagerInterface $entityManager)
    {
        $this->jwtManager       = $jwtManager;
        $this->entityManager    = $entityManager;
    }

    /**
     * A new websocket connection
     *
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections[] = $conn;
        $conn->send('..:: Hello from the Notification Center ::..');
        echo "New connection \n";
    }

    /**
     * Handle message sending
     *
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $messageData = json_decode(trim($msg));
        if(isset($messageData->userData)){
            //1st app message with connected user
            $token_user = $messageData->userData;

            //a user auth, else, app sending message auth
            echo "Check user credentials\n";
            //get credentials
            //$jwt_manager = $this->container->get('lexik_jwt_authentication.jwt_manager');
            $token = new JWTUserToken();
            $token->setRawToken($token_user);
            $payload = $this->jwtManager->decode($token);

            //getUser by email
            if(!$user = $this->getUserByEmail($payload['username'])){
                $from->close();
            }
            echo "User found : ".$user->getFirstname() . "\n";
            $index_connection = $payload['username'];

            $all_connections = $this->connections;
            foreach($all_connections as $key => $conn){
                if($conn === $from){
                    $this->connections[$index_connection] = $from;
                    $from->send('..:: Connected as '.$index_connection.'  ::..');
                    unset($this->connections[$key]);
                    break;
                } else {
                    continue;
                }

            }
        } else {
            //error
            $from->send("You're not able to do that!");
        }

    }

    /**
     * A connection is closed
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        foreach($this->connections as $key => $conn_element){
            if($conn === $conn_element){
                unset($this->connections[$key]);
                break;
            }
        }
    }

    /**
     * Error handling
     *
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->send("Error : " . $e->getMessage());
        $conn->close();
    }


    /**
     * Get user from email credential
     *
     * @param $email
     * @return false|User
     */
    protected function getUserByEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }

        $repo = $this->entityManager->getRepository(AppUser::class);

        $user = $repo->findOneBy(array('email' => $email));

        if($user && $user instanceof User){
            return $user;
        } else {
            return false;
        }

    }

}




