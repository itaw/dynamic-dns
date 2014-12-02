<?php

namespace itaw\AppBundle\Service;

use itaw\DataBundle\Entity\Accessor;

class MailerService
{
    private $mailer;
    private $templating;

    public function __construct($mailer, $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param Accessor $accessor
     */
    public function sendAccessorRegistrationMail(Accessor $accessor)
    {
        $this->sendMessage(
            'ITAW DynDns Registration',
            'no-reply@weber-elektronik.de',
            $accessor->getEmail(),
            'itawAppBundle:Email:registration_complete.html.twig',
            array(
                'accessor' => $accessor
            )
        );
    }

    /**
     * @param $subject
     * @param $from
     * @param array $to
     * @param $template
     * @param array $parameters
     */
    private function sendMessage($subject, $from, $to = array(), $template, $parameters = array())
    {
        $body = $this->templating->render($template, $parameters);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body);

        $this->mailer->send($message);
    }
}