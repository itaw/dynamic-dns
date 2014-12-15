<?php

namespace itaw\DnsBundle\Controller;

use itaw\DataBundle\Entity\Domain;
use itaw\DataBundle\Entity\DomainAddress;
use Proxy\Factory;
use Proxy\Response\Filter\RemoveEncodingFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DnsRedirectController extends Controller
{
    public function redirectAction(Request $request)
    {
        /** @var $domain Domain */
        $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneByName($request->getHost());

        if (!$domain) {
            throw new BadRequestHttpException(
                sprintf('The Domain %s is not defined for redirection!', $request->getHost())
            );
        }

        /** @var $latestAddress DomainAddress */
        $latestAddress = $this->getDoctrine()->getRepository('itawDataBundle:DomainAddress')->findBy(
            array(
                'domain' => $domain
            ),
            array(
                'openDate' => 'desc'
            ),
            1
        )[0];

        if (!$latestAddress) {
            throw new BadRequestHttpException(
                sprintf('The Domain %s has no defined redirection addresses yet!', $domain->getName())
            );
        }

        $redirectUrl = $request->getScheme() . '://' . $latestAddress->getIp();

        if ($request->getPort() != 80) {
            $redirectUrl .= ':' . $request->getPort();
        }

        $proxy = Factory::create();
        $proxy->addResponseFilter(new RemoveEncodingFilter());

        $response = $proxy->forward($request)->to($redirectUrl);

        return $response;
    }
}