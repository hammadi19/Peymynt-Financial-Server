<?php

namespace App\Base\Handler;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;


/**
 * Class EmailHandler
 * @package App\Base\Handler
 */
class EmailHandler
{

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * @var \Swift_Mailer $mailer
     */
    protected $mailer;

    /**
     * @var \Twig_Environment $twig
     */
    protected $twig;

    /**
     * @var $emailTemplate
     */
    protected $emailTemplate;

    /**
     * @var $toEmailsArray
     */
    protected $toEmailsArray;

    /**
     * @var $ccEmailsArray
     */
    protected $ccEmailsArray;

    /**
     * @var $bccEmailsArray
     */
    protected $bccEmailsArray;

    /**
     * @var $fromArray
     */
    protected $fromArray;

    /**
     * @string $emailSubject
     */
    protected $emailSubject;

    /**
     * @string $emailBody
     */
    protected $bodyHtml;

    /**
     * @var $attachment
     */
    protected $attachment;

    /**
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param ContainerInterface $container
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig , ContainerInterface $container){
        $this->container = $container;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }


    /**
     * Set start ups
     *
     * @param $fromArray
     * @param $toEmailsArray
     * @param $emailSubject
     * @param array $ccEmailsArray
     * @param array $bccEmailsArray
     * @return $this
     */
    public function setInitials( $fromArray ,$toEmailsArray, $emailSubject ,array $ccEmailsArray=[], array $bccEmailsArray=[]){
        $this->fromArray      = $fromArray;
        $this->emailSubject   = $emailSubject;
        $this->toEmailsArray  = $toEmailsArray;
        $this->ccEmailsArray  = $ccEmailsArray;
        $this->bccEmailsArray = $bccEmailsArray;
        return $this;
    }

    /**
     * Set Dictionary providers
     *
     * @param $dataArray
     * @return $this
     */
    public function setDictionary($dataArray){
        $this->fromArray      = $dataArray['from'];
        $this->emailSubject   = $dataArray['subject'];
        $this->toEmailsArray  = $dataArray['to'];
        $this->ccEmailsArray  = $dataArray['cc'];
        $this->bccEmailsArray = $dataArray['bcc'];

        // set email template & data
        $this->setTemplates(
            $dataArray['email_template'],
            $dataArray['email_params']
        );

        return $this;
    }

    /**
     * Reset all values
     */
    public function reset(){
        $this->fromArray      = array();
        $this->emailSubject   = array();
        $this->toEmailsArray  = array();
        $this->ccEmailsArray  = array();
        $this->bccEmailsArray = array();
        $this->emailTemplate  = '';
        $this->bodyHtml       = '';
    }

    /**
     * Set template and email template parameters
     *
     * @param $emailTemplate
     * @param array $templateParams
     * @return $this
     */
    public function setTemplates($emailTemplate, array $templateParams = []){
        $this->bodyHtml = $this->twig->render(
            'email_templates/'.$emailTemplate.'.html.twig',
            $templateParams
        );
        return $this;
    }

    /**
     * Pass full template path with name
     *
     * @param $templateWithPath
     * @param array $templateParams
     * @return $this
     */
    public function setTemplateWithPath($templateWithPath, array $templateParams = []){
        $this->emailTemplate  = $this->twig->loadTemplate($templateWithPath);
        $this->bodyHtml = $this->emailTemplate->renderBlock('body_html', $templateParams);
        return $this;
    }

    /**
     * View twig generated html
     *
     * @return mixed
     */
    public function getEmailBodyHtml(){
        return $this->bodyHtml;
    }


    public function setAttachment($attachment) {
        $this->attachment = $attachment;
        return $this;
    }

    /**
     * Send email
     *
     * @return int|string
     */
    public function sendEmail(){

        //$headers = "From:" . $this->fromArray[0];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = sprintf('From: %s',$this->fromArray);
        $response = mail($this->toEmailsArray[0],$this->emailSubject,$this->bodyHtml,implode("\r\n", $headers));
        /*
        $message = (new \Swift_Message($this->emailSubject))
            ->setFrom($this->fromArray)
            ->setTo($this->toEmailsArray)
            ->setBody(
                $this->bodyHtml,
                'text/html'
            );

        try {
            $response = $this->mailer->send($message);
        }catch (\Exception $ex) {
            return $ex->getMessage();
        }
        */
        return $response;
    }

    /**
     * Send attachment via email
     *
     * @return int|string
     */
    public function sendEmailAttachment() {

        $message = \Swift_Message::newInstance()
            ->setSubject($this->emailSubject)
            ->setFrom($this->fromArray)
            ->setTo($this->toEmailsArray)
            ->setCc($this->ccEmailsArray)
            ->setBcc($this->bccEmailsArray)
            ->setBody($this->bodyHtml, 'text/html')
            ->attach(\Swift_Attachment::fromPath($this->attachment));

        try {
            $response = $this->mailer->send($message);
        }catch (\Exception $ex) {
            return $ex->getMessage();
        }
        return $response;
    }

}//@