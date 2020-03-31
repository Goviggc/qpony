<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use App\Utils\MaxNumber;

class BaseController extends AbstractController
{
    /**
     * @var int
     */
    private $rows = 10;

    /**
     * @param int $rows
     * @return FormInterface
     */
    private function getForm(int $rows = 10): FormInterface
    {
        /**
         * @param FormBuilderInterface $form
         * @param int $id
         * @return FormBuilderInterface
         */
        function addNumberValue(FormBuilderInterface $form, int $id): FormBuilderInterface
        {
            return $form->add("number-$id", IntegerType::class, [
                "label" => "Liczba $id: ",
                "required" => false
            ]);
        }

        $form = $this->createFormBuilder();
        for($i = 1; $i <= $rows; $i++)
            $form = addNumberValue($form, $i);

        $form->add("Wyslij", SubmitType::class);

        return $form->getForm();
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function indexAction(Request $request): Response
    {
        $form = $this->getForm($this->rows);
        $form->handleRequest($request);

        $returnValues = null;
        if($form->isSubmitted() && $form->isValid())
        {
            $returnValues = [];

            foreach ($form->getData() as $key=>$data)
            {
                if(gettype($data) !== "integer")
                    continue;

                $max = new MaxNumber($data);

                $returnValues[] = [
                    "id" => $key,
                    "max" => $max->getMaxNumber(),
                    "value" => $data
                ];
            }
        }

        return $this->render("base.html.twig",[
            "form" => $form->createView(),
            "data" => $returnValues
        ]);
    }
}