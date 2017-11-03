<?php

namespace FrontendBundle\Controller;

use CoreBundle\Entity\Share;
use CoreBundle\Exception\DuplicateEmailException;
use CoreBundle\Exception\InvalidShareKeyException;
use CoreBundle\Service\ShareService;
use CoreBundle\Service\UserService;
use FrontendBundle\Form\Entity\DownloadShareFormEntity;
use FrontendBundle\Form\Entity\RegistrationFormEntity;
use FrontendBundle\Form\Entity\UploadFileFormEntity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $downloadForm = $this->buildDownloadForm();
        $error = null;
        if ($request->isMethod("POST")) {
            $downloadForm->handleRequest($request);

            if ($downloadForm->isSubmitted() && $downloadForm->isValid()) {
                /** @var DownloadShareFormEntity $entity */
                $entity = $downloadForm->getData();

                /** @var ShareService $shareService */
                $shareService = $this->get(ShareService::class);

                try {
                    $share = $shareService->downloadShare($entity->getShareKey(), $entity->getPassword());
                    $this->downloadShare($share);
                } catch (InvalidShareKeyException $e) {
                    $error = $e->getMessage();
                }
            }
        }

        return $this->render('FrontendBundle:Default:index.html.twig', ["downloadForm" => $downloadForm->createView(), "error" => $error]);
    }

    /**
     * @Route("/secured/member_download/{key}", name="member_download")
     */
    public function memberDownloadAction($key, Request $request, UserInterface $user)
    {
        $error = null;

        try {
            /** @var ShareService $shareService */
            $shareService = $this->get(ShareService::class);
            $share = $shareService->memberDownload($key, $user);
            $this->downloadShare($share);
        } catch (InvalidShareKeyException $e) {
            $error = $e->getMessage();
        }

        if ($error != null) {
            $this->addFlash("error", $error);
            return $this->redirectToRoute("member_home");
        }
    }

    /**
     * @Route("/secured/member_home", name="member_home")
     */
    public function memberHomeAction(Request $request, UserInterface $userInterface)
    {
        $uploadForm = $this->buildUploadForm();

        /** @var ShareService $shareService */
        $shareService = $this->get(ShareService::class);

        $topShares = $shareService->findLatestShares($userInterface);

        if ($request->isMethod("POST")) {
            $uploadForm->handleRequest($request);
            if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
                /** @var UploadFileFormEntity $entity */
                $entity = $uploadForm->getData();
                $share = $shareService->createNewShare($userInterface, $entity->getFile(), $entity->getPassword());

                $this->addFlash("notice", "File uploaded successfully, share key is: ".$share->getShareKey());

                return $this->redirectToRoute("member_home");
            }
        }

        return $this->render("FrontendBundle:Default:member_home.html.twig", ["uploadForm" => $uploadForm->createView(), 'shares' => $topShares]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        $registerForm = $this->buildRegistrationForm();
        $loginError = $authUtils->getLastAuthenticationError();
        if ($loginError != null) {
            $loginError = "Invalid credentials";
        }
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

        return $this->render("FrontendBundle:Default:login.html.twig", ["register_form" => $registerForm->createView(), "error" => $error, "loginError" => $loginError]);
    }

    /**
     * @param Share $share
     */
    private function downloadShare(Share $share)
    {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$share->getOriginalFilename());
        header("Content-Length: ".$share->getFileSize());
        $resource = $share->getStorageHandler();

        while(!feof($resource)) {
            echo fread($resource, 1024);
        }

        fclose($resource);
    }

    /**
     * @return Form
     */
    private function buildDownloadForm()
    {
        $entity = new DownloadShareFormEntity();

        return $this->createFormBuilder($entity)
                ->add("shareKey", TextType::class, ['label' => 'Share Key'])
                ->add("password", PasswordType::class, ['label' => 'Password (Optional)', 'required'=>false])
                ->getForm();
    }

    /**
     * @return Form
     */
    private function buildUploadForm()
    {
        $entity = new UploadFileFormEntity();

        return $this->createFormBuilder($entity)
                ->add('file', FileType::class, ['label' => 'File'])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Password fields must match.',
                    'required' => false,
                    'first_options' => ['label' => 'Password (Optional)'],
                    'second_options' => ['label' => 'Confirm Password']
                ])
                ->getForm();
    }

    /**
     * @return Form
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
