<?php


namespace App\Controller;

use App\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FeedbackController extends AbstractController
{

    /**
     * @Route("/feedbacks", name="feedbacks_index")
     */
    public function index()
    {


        $feedbacks = $this->getDoctrine()
            ->getRepository(Feedback::class)
            ->findAll();

        return $this->render('feedbacks/index.html.twig', [
            'feedbacks' => $feedbacks,
        ]);
    }


    /**
     * @Route("/", name="feedbacks_create")
     */

    public function create(Request $request, \Swift_Mailer $mailer): Response
    {

        $feedback = new Feedback();


        $form = $this->createFormBuilder($feedback)
//            ->setAction($this->generateUrl('feedbacks_new'))
            ->setMethod('POST')
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'error_bubbling' => true
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'error_bubbling' => true
            ])
            ->add('text', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'error_bubbling' => true
            ])
            ->add('Save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mt-3']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $request->isXmlHttpRequest()) {

            // $request->getContent()
//             return new JsonResponse(['status' => 'test', 'message' => $request->request->all()], 400);

            if (!$form->isValid()) {

                $errorsString = (string) $form->getErrors();
                return new JsonResponse($errorsString, 400);
            }


            try {
//                $data = $form->getData();
                $feedback->setName($form->get('name')->getData());
                $feedback->setEmail($form->get('email')->getData());
                $feedback->setText($form->get('text')->getData());
                $feedback->setCreatedAt(new \DateTime());
                $feedback->setRemoteAddr($_SERVER['REMOTE_ADDR']);


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($feedback);
                $entityManager->flush();


                $nameFrom = 'admin@zayaets.ru';
                $message = (new \Swift_Message('Feedback'))
                    ->setFrom($nameFrom, 'Admin')
                    ->setTo($feedback->getEmail())
                    ->setBody(
                        $this->renderView(
                            'feedbacks/email.html.twig',
                            ['feedback' => $feedback]
                        ),
                        'text/html'
                    );

                if($mailer->send($message)) {
                    return new JsonResponse([
                        'status' => 'success',
                        'message' => 'Thanks! Your feedback successfully added! And we sent copy of it on your email.'
                    ], 200);
                } else {
                    return new JsonResponse([
                        'status' => 'error',
                        'message' => 'Email has not been sent',
                    ], 400);
                }

            } catch (\Exception $e) {
                return new JsonResponse([
                    'status' => 'error',
//                    'code'    => $e->getCode(),
//                    'message' => $e->getMessage(),
                    'message' => 'Error occurred! Feedback cannot be added. Please, try again later.'
                ], 400);
            }

//            $res = [
//                'status' => 'success',
//                'message' => 'Your feedback successfully added!'
//            ];
//
//            return new JsonResponse($res, 200);
        }

        return $this->render('feedbacks/create.html.twig', [
            'form' => $form->createView(),
        ]);

    }



}