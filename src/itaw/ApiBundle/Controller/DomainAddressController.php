<?php

namespace itaw\ApiBundle\Controller;

use Doctrine\DBAL\Exception\ConstraintViolationException;
use itaw\DataBundle\Entity\Accessor;
use itaw\DataBundle\Entity\Domain;
use itaw\DataBundle\Entity\DomainAddress;
use itaw\Util\PasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class DomainAddressController extends AbstractApiController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        //validate
        if ($request->get('domain', '') == ''
            || $request->get('username', '') == ''
            || $request->get('pass', '') == ''
            || $request->get('ipaddr', '') == ''
        ) {
            throw new BadRequestHttpException(sprintf('All parameters must be provided!'));
        }

        //get domain
        /** @var $domain Domain */
        $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneByName(
            $request->get('domain')
        );

        if (!$domain) {
            throw new BadRequestHttpException(sprintf('The requested Domain is not configured!'));
        }

        //get accessor
        /** @var $accessor Accessor */
        $accessor = $this->getDoctrine()->getRepository('itawDataBundle:Accessor')->findOneByUsername(
            $request->get('username')
        );

        if (!$accessor) {
            throw new AuthenticationException(sprintf('Bad Accessor Name %s!', $request->get('username')));
        }

        //prepare password
        $encodedPassword = PasswordEncoder::encode($request->get('pass'), $accessor->getSalt());

        //check password
        if ($encodedPassword != $accessor->getPassword()) {
            throw new AuthenticationException(sprintf('Bad Password!'));
        }

        //check if accessor is linked with domain
        if (!$accessor->hasDomain($domain)) {
            throw new AuthenticationException(
                sprintf(
                    'The accessor %s is not yet linked with domain %s',
                    $accessor->getUsername(),
                    $domain->getName()
                )
            );
        }

        //add address
        $address = new DomainAddress();
        $address->setDomain($domain)
            ->setIp($request->get('ipaddr'))
            ->setSourceIp($request->getClientIp())
            ->setOpenDate(new \DateTime('now'));

        //validate
        $validator = $this->get('validator');
        $errors = $validator->validate($address);
        if (count($errors) > 0) {
            throw new \Exception(sprintf((string)$errors));
        }

        //write
        $em = $this->getDoctrine()->getManager();
        $em->persist($address);
        $em->flush();

        return new Response($this->serializeJson($address));
    }
}