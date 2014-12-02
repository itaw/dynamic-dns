<?php

namespace itaw\AppBundle\Controller;

use itaw\DataBundle\Entity\Accessor;
use itaw\Util\PasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
            'itawAppBundle:Domain:Accessor.html.twig',
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

        if ($request->get('sent', 0) == 1) {
            $salt = rand(82, 6846304) . (new \DateTime('now'))->format('Ymdgis');
            $encodedPassword = PasswordEncoder::encode($request->get('password'), $salt);

            $accessor = new Accessor();
            $accessor
                ->setActive(true)
                ->setOpenUser($this->getUser())
                ->setCreationDate(new \DateTime('now'))
                ->setEmail($request->get('email'))
                ->setSalt($salt)
                ->setPassword($encodedPassword)
                ->setUsername($request->get('username'));

            //validate
            $validator = $this->get('validator');
            $errors = $validator->validate($accessor);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $session->getFlashBag()->add('error', $error);
                }

                return $this->render('itawAppBundle:Accessor:create.html.twig');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($accessor);
            $em->flush();

            //send info mail
            $this->container->get('itaw.email')->sendAccessorRegistrationMail($accessor);

            return $this->redirectToRoute('accessors_collection');
        }

        return $this->render('itawAppBundle:Accessor:create.html.twig');
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

            return $this->redirectToRoute('accessors_collection');
        }

        return $this->render(
            'itawAppBundle:Accessor:delete.html.twig',
            array(
                'domain' => $accessor
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

}