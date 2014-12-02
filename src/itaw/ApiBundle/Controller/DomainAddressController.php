<?php

namespace itaw\ApiBundle\Controller;

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
        if ($request->get('domain', '') == ''
            || $request->get('username', '') == ''
            || $request->get('password', '') == ''
        ) {
            throw new BadRequestHttpException(sprintf('All parameters must be provided!'));
        }

        $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneByName(
            $request->get('domain')
        );

        if (!$domain) {
            throw new BadRequestHttpException(sprintf('The requested Domain is not configured!'));
        }

        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($request->get('username'));

        if (!$user) {
            throw new AuthenticationException(sprintf('Bad Username!'));
        }

        $encoderService = $this->get('security.encoder_factory');
        $encoder = $encoderService->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($request->get('password'), $user->getSalt());

        if ($encodedPassword != $user->getPassword()) {
            throw new AuthenticationException(sprintf('Bad Password!'));
        }

        return new Response(json_encode(array(null)));
    }
}