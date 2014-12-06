<?php

namespace itaw\AppBundle\Controller;

use itaw\DataBundle\Entity\Domain;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function collectionAction(Request $request)
    {
        if ($request->get('name', null) != null && $request->get('name', null) != 'localhost') {
            $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneByName(
                $request->get('name')
            );

            return $this->redirectToRoute(
                'domains_object',
                array(
                    'domainId' => $domain->getId()
                )
            );
        }

        $domains = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findAll();

        return $this->render(
            'itawAppBundle:Domain:collection.html.twig',
            array(
                'domains' => $domains
            )
        );
    }

    /**
     * @param $domainId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function objectAction($domainId)
    {
        $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneById($domainId);

        return $this->render(
            'itawAppBundle:Domain:object.html.twig',
            array(
                'domain' => $domain
            )
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $session = $request->getSession();

        $ajax = $request->get('ajax', 0);

        if ($request->get('sent', 0) == 1) {
            $domain = new Domain();
            $domain
                ->setName($request->get('name'))
                ->setCreationDate(new \DateTime('now'))
                ->setOpenUser($this->getUser())
                ->setActive(true);

            //validate
            $validator = $this->get('validator');
            $errors = $validator->validate($domain);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $session->getFlashBag()->add('error', $error);
                }

                if ($ajax == 0) {
                    return $this->render('itawAppBundle:Domain:create.html.twig');
                }

                $response = new Response(
                    json_encode(
                        array(
                            'status' => 'error',
                            'errors' => $errors
                        )
                    )
                );
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($domain);
            $em->flush();

            if ($ajax == 0) {
                return $this->redirectToRoute('domains_collection');
            }

            $response = new Response(
                json_encode(
                    array(
                        'status' => 'success'
                    )
                )
            );
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return $this->render('itawAppBundle:Domain:create.html.twig');
    }

    /**
     * @param Request $request
     * @param $domainId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $domainId)
    {
        $session = $request->getSession();
        $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneById($domainId);

        if ($request->get('sent', 0) == 1) {
            $domain
                ->setName($request->get('name'))
                ->setEditDate(new \DateTime('now'))
                ->setEditUser($this->getUser());

            //validate
            $validator = $this->get('validator');
            $errors = $validator->validate($domain);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $session->getFlashBag()->add('error', $error);
                }

                return $this->render(
                    'itawAppBundle:Domain:update.html.twig',
                    array(
                        'domain' => $domain
                    )
                );
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('domains_collection');
        }

        return $this->render(
            'itawAppBundle:Domain:update.html.twig',
            array(
                'domain' => $domain
            )
        );
    }

    /**
     * @param Request $request
     * @param $domainId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $domainId)
    {
        $session = $request->getSession();
        $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneById($domainId);

        if ($request->get('sent', 0) == 1) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($domain);
            $em->flush();

            return $this->redirectToRoute('domains_collection');
        }

        return $this->render(
            'itawAppBundle:Domain:delete.html.twig',
            array(
                'domain' => $domain
            )
        );
    }

    /**
     * @param Request $request
     * @param $domainId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function toggleActiveAction(Request $request, $domainId)
    {
        $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneById($domainId);

        $domain
            ->setActive(!$domain->getActive())
            ->setEditDate(new \DateTime('now'))
            ->setEditUser($request->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('domains_collection');
    }

}