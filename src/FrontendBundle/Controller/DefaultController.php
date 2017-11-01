<?php

namespace FrontendBundle\Controller;

use CoreBundle\Exception\DuplicateEmailException;
use CoreBundle\Service\UserService;
use FrontendBundle\Form\Entity\RegistrationFormEntity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('FrontendBundle:Default:index.html.twig');
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $registerForm = $this->buildRegistrationForm();

        $error = null;
        if ($request->isMethod("POST")) {
            $registerForm->handleRequest($request);
            if ($registerForm->isSubmitted() && $registerForm->isValid()) {
                /** @var UserService $userService */
                $userService = $this->get(UserService::class);

                try {
                    /** @var RegistrationFormEntity $entity */
                    $entity = $registerForm->getData();
                    $userService->register($entity->getEmail(), $entity->getPassword());
                    $this->addFlash("notice", "Registration successful, please login to proceed.");
                    return $this->redirectToRoute("login");
                } catch (DuplicateEmailException $e) {
                    $error = $e->getMessage();
                }
            }
        }

        return $this->render("FrontendBundle:Default:login.html.twig", ["register_form" => $registerForm->createView(), "error" => $error]);
    }

    /**
     * @var Form
     */
    private function buildRegistrationForm()
    {
        $entity = new RegistrationFormEntity();

        return $this->createFormBuilder($entity)
                ->add('email', EmailType::class)
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Password fields must match.',
                    'required' => true,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Confirm Password']
                ])
                ->getForm();
    }
}
