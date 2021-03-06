<?php

namespace itaw\AppBundle\Controller;

use itaw\DataBundle\Entity\Accessor;
use itaw\Util\PasswordEncoder;
use itaw\Util\PasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AccessorController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function collectionAction(Request $request)
    {
        $accessors = $this->getDoctrine()->getRepository('itawDataBundle:Accessor')->findAll();

        return $this->render(
            'itawAppBundle:Accessor:collection.html.twig',
            array(
                'accessors' => $accessors
            )
        );
    }

    /**
     * @param $accessorId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function objectAction($accessorId)
    {
        $accessor = $this->getDoctrine()->getRepository('itawDataBundle:Accessor')->findOneById($accessorId);

        return $this->render(
            'itawAppBundle:Accessor:object.html.twig',
            array(
                'accessor' => $accessor
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
        $domains = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findBy(array('active' => true));

        if ($request->get('sent', 0) == 1) {
            $password = PasswordGenerator::generate();
            $salt = rand(82, 6846304) . (new \DateTime('now'))->format('Ymdgis');
            $encodedPassword = PasswordEncoder::encode($password, $salt);

            $accessor = new Accessor();
            $accessor
                ->setActive(true)
                ->setOpenUser($this->getUser())
                ->setCreationDate(new \DateTime('now'))
                ->setEmail($request->get('email'))
                ->setSalt($salt)
                ->setPassword($encodedPassword)
                ->setUsername($request->get('username'));

            //link domains
            if ($request->get('domains') != '') {
                $domainIds = explode('|', $request->get('domains'));

                foreach ($domainIds as $domainId) {
                    $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneById($domainId);

                    if (!$domain) {
                        throw new BadRequestHttpException(
                            sprintf('The Domain with the id %s does not exist!', $domainId)
                        );
                    }

                    $accessor->addDomain($domain);
                }
            }

            //validate
            $validator = $this->get('validator');
            $errors = $validator->validate($accessor);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $session->getFlashBag()->add('error', $error);
                }

                return $this->render(
                    'itawAppBundle:Accessor:create.html.twig',
                    array(
                        'domains' => $domains
                    )
                );
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($accessor);
            $em->flush();

            //send info mail
            $this->get('itaw.email')->sendAccessorRegistrationMail($accessor, $password);

            return $this->redirectToRoute('accessors_collection');
        }

        return $this->render(
            'itawAppBundle:Accessor:create.html.twig',
            array(
                'domains' => $domains
            )
        );
    }

    /**
     * @param Request $request
     * @param $accessorId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $accessorId)
    {
        $accessor = $this->getDoctrine()->getRepository('itawDataBundle:Accessor')->findOneById($accessorId);

        if ($request->get('sent', 0) == 1) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($accessor);
            $em->flush();

            //send info mail
            $this->get('itaw.email')->sendAccessorDeletedMail($accessor);

            return $this->redirectToRoute('accessors_collection');
        }

        return $this->render(
            'itawAppBundle:Accessor:delete.html.twig',
            array(
                'accessor' => $accessor
            )
        );
    }

    /**
     * @param Request $request
     * @param $accessorId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function toggleActiveAction(Request $request, $accessorId)
    {
        $accessor = $this->getDoctrine()->getRepository('itawDataBundle:Accessor')->findOneById($accessorId);

        $accessor
            ->setActive(!$accessor->getActive())
            ->setEditDate(new \DateTime('now'))
            ->setEditUser($request->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('accessors_collection');
    }

    public function linkDomainAction(Request $request, $accessorId)
    {
        $accessor = $this->getDoctrine()->getRepository('itawDataBundle:Accessor')->findOneById($accessorId);
        $session = $request->getSession();

        if (!$accessor) {
            throw $this->createNotFoundException(sprintf('The Accessor with the ID %s was not found!', $accessorId));
        }

        $domains = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findBy(array('active' => true));

        if (!$domains) {
            $session->getFlashBag()->add('error', sprintf('No active Domains found!'));

            return $this->redirectToRoute('accessors_object', array('accessorId' => $accessorId));
        }

        if ($request->get('sent', 0) == 1) {
            $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneById(
                $request->get('domain')
            );

            if (!$domain) {
                throw new BadRequestHttpException(sprintf('Domain not found!'));
            }

            if ($accessor->hasDomain($domain)) {
                $session->getFlashBag()->add(
                    'error',
                    sprintf('The requested Domain is already linked with this Accessor!')
                );

                return $this->redirectToRoute(
                    'accessors_domains_link',
                    array(
                        'accessorId' => $accessorId
                    )
                );
            }

            $accessor->addDomain($domain);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            //mail
            $this->get('itaw.email')->sendAccessorLinkedDomainMail($accessor, $domain);

            return $this->redirectToRoute('accessors_object', array('accessorId' => $accessorId));
        }

        return $this->render(
            'itawAppBundle:Accessor:link.domain.html.twig',
            array(
                'accessor' => $accessor,
                'domains' => $domains
            )
        );
    }

    public function unlinkDomainAction(Request $request, $accessorId, $domainId)
    {
        $accessor = $this->getDoctrine()->getRepository('itawDataBundle:Accessor')->findOneById($accessorId);
        $session = $request->getSession();

        if (!$accessor) {
            throw $this->createNotFoundException(sprintf('The Accessor with the ID %s was not found!', $accessorId));
        }

        $domain = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findOneById($domainId);

        if (!$domain) {
            throw $this->createNotFoundException(sprintf('The Domain with the ID %s was not found!', $domainId));
        }

        if ($request->get('sent', 0) == 1) {
            $accessor->removeDomain($domain);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            //mail
            $this->get('itaw.email')->sendAccessorUnLinkedDomainMail($accessor, $domain);

            return $this->redirectToRoute('accessors_object', array('accessorId' => $accessorId));
        }

        return $this->render(
            'itawAppBundle:Accessor:unlink.domain.html.twig',
            array(
                'accessor' => $accessor,
                'domain' => $domain
            )
        );
    }

}