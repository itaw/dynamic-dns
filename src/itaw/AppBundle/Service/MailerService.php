<?php

namespace itaw\AppBundle\Service;

use itaw\DataBundle\Entity\Accessor;
use itaw\DataBundle\Entity\Domain;

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
    public function sendAccessorRegistrationMail(Accessor $accessor, $plainPassword)
    {
        $this->sendMessage(
            'ITAW DynDns | Registration',
            'no-reply@weber-elektronik.de',
            $accessor->getEmail(),
            'itawEmailBundle:Accessor:registration_completed.html.twig',
            array(
                'accessor' => $accessor,
                'plainPassword' => $plainPassword
            )
        );
    }

    /**
     * @param Accessor $accessor
     */
    public function sendAccessorDeletedMail(Accessor $accessor)
    {
        $this->sendMessage(
            'ITAW DynDns | Accessor Deleted',
            'no-reply@weber-elektronik.de',
            $accessor->getEmail(),
            'itawEmailBundle:Accessor:deleted.html.twig',
            array(
                'accessor' => $accessor
            )
        );
    }

    /**
     * @param Accessor $accessor
     * @param Domain $domain
     */
    public function sendAccessorLinkedDomainMail(Accessor $accessor, Domain $domain)
    {
        $this->sendMessage(
            'ITAW DynDns | Accessor Linked',
            'no-reply@weber-elektronik.de',
            $accessor->getEmail(),
            'itawEmailBundle:Accessor:linked_domain.html.twig',
            array(
                'accessor' => $accessor,
                'domain' => $domain
            )
        );
    }

    /**
     * @param Accessor $accessor
     * @param Domain $domain
     */
    public function sendAccessorUnLinkedDomainMail(Accessor $accessor, Domain $domain)
    {
        $this->sendMessage(
            'ITAW DynDns | Accessor UnLinked',
            'no-reply@weber-elektronik.de',
            $accessor->getEmail(),
            'itawEmailBundle:Accessor:unlinked_domain.html.twig',
            array(
                'accessor' => $accessor,
                'domain' => $domain
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
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}