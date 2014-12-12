<?php

namespace itaw\ApiBundle\Controller;

use itaw\DataBundle\Entity\Accessor;
use itaw\DataBundle\Entity\Domain;
use itaw\DataBundle\Entity\DomainAddress;
use itaw\Util\PasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class DomainAddressController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        //validate
        if ($request->get('domain', '') == ''
            || $request->get('accessor', '') == ''
            || $request->get('password', '') == ''
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
            $request->get('accessor')
        );

        if (!$accessor) {
            throw new AuthenticationException(sprintf('Bad Accessor Name %s!', $request->get('accessor')));
        }

        //prepare password
        $encodedPassword = PasswordEncoder::encode($request->get('password'), $accessor->getSalt());

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

        //add link
        $address = new DomainAddress();
        $address->setDomain($domain)
            ->setIp('127.0.0.1')
            ->setSourceIp($request->getClientIp())
            ->setOpenDate(new \DateTime('now'));

        return new Response(json_encode(array($address)));
    }
}