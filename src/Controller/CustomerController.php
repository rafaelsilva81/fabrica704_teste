<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Customer;

/**
 * @Route("/api/customers", name="customer_")
 */
class CustomerController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    } 


    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index()
    {

        $customers = $this->getDoctrine()->getRepository(Customer::class)->findAll();

        return $this->json([
            'data' => $customers
        ]);
    }

    /**
     * @Route("/{customerId}", name="show", methods={"GET"})
     */
    public function show($customerId)
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($customerId);

        if (!$customer) {
            return $this->json([
                "error" => "Não foi possível encontrar um Customer com o id passado, tente novamente"
            ], 400);
        }
        
        return $this->json([
            "data" => $customer
        ], 200);
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $data = $request->request->all();

        $customer = new Customer();

        try {
            $customer->setName($data['name']);
            $customer->setEmail($data['email']);
            $customer->setPassword($this->passwordEncoder->encodePassword(
                $customer,
                $data['password']
            ));
            $customer->setDocument($data['document']);
            $customer->setIpLocation($request->getClientIp());
            $customer->setTimestamps(new \DateTime('now'), new \DateTimeZone('America/Fortaleza'));
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Não foi possivel criar novo Customer, verifique os dados enviados',
                'description' => $e->getMessage(),
            ], 400);
        }

        $customerCheck = $this->getDoctrine()->getRepository(Customer::class)->findOneByEmail($data['email']);

        if ($customerCheck) {
            return $this->json([
                'error' => 'Já existe um Customer com esse endereço de e-mail cadastrado',
            ], 400);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($customer);
        $manager->flush();

        return $this->json([
            'data' => 'Customer adicionado com sucesso'
        ], 200);

    }

    /**
     * @Route("/{customerId}", name="update", methods={"PUT"})
     */
    public function update($customerId, Request $request)
    {
        $data = $request->request->all();

        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($customerId);

        if(!$customer) {
            return $this->json([
                'error' => 'Não foi possivel criar novo Customer, verifique os dados enviados',
            ], 400);
        }

        try {
            $customer->setName($data['name']);
            $customer->setEmail($data['email']);
            $customer->setPassword($this->passwordEncoder->encodePassword(
                $customer,
                $data['password']
            ));
            $customer->setDocument($data['document']);
            $customer->setIpLocation($request->getClientIp());
            $customer->setTimestamps(new \DateTime('now'), new \DateTimeZone('America/Fortaleza'));
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Não foi possivel criar novo Customer, verifique os dados enviados',
                'description' => $e->getMessage(),
            ], 400);
        }

        $customerCheck = $this->getDoctrine()->getRepository(Customer::class)->findOneByEmail($data['email']);

        if ($customerCheck) {
            return $this->json([
                'error' => 'Já existe um Customer com esse endereço de e-mail cadastrado',
            ], 400);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($customer);
        $manager->flush();

        return $this->json([
            'data' => 'Customer alterado com sucesso'
        ], 200);
        
    }

    /**
     * @Route("/{customerId}", name="delete", methods={"DELETE"})
     */
    public function delete($customerId)
    {
        
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($customerId);

        if (!$customer) {
            return $this->json([
                "error" => "Não foi possível encontrar um Customer com o id passado, tente novamente"
            ], 400);
        }
        
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($customer);
        $manager->flush();

        return $this->json([
            "data" => "Customer removido com sucesso"
        ], 200);


    }

}
