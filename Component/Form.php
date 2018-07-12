<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Idk\LegoBundle\Component;

use Idk\LegoBundle\ComponentResponse\ErrorComponentResponse;
use Idk\LegoBundle\ComponentResponse\SuccessComponentResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Idk\LegoBundle\Service\MetaEntityManager;




class Form extends Component{

    private $form;
    private $mem;
    private $formFactory;

    public function __construct(MetaEntityManager $mem, FormFactoryInterface $formFactory){
        $this->mem = $mem;
        $this->formFactory = $formFactory;
    }

    protected function init(){

    }

    protected function requiredOptions(){
        return [];
    }

    public function getTemplate($name = 'index'){
        return 'IdkLegoBundle:Component\\FormComponent:'.$name.'.html.twig';
    }

    public function getTemplateParameters(){
        return ['form' => $this->form->createView(), 'theme' => $this->getOption('theme','IdkLegoBundle:Form:lego_base_fields.html.twig')];
    }

    public function generateForm($entity){
        if(!$this->getOption('form',null)){
            $formBuilder = $this->formFactory->createBuilder(FormType::class, $entity, ['allow_extra_fields'=>true]);
            foreach($this->mem->generateFormFields($this->getConfigurator()->getEntityName()) as $field){
                if(!in_array($field->getName(), $this->getOption('fields_exclude',[]))) {
                    $field->addIn($this->formFactory, $formBuilder, $this->mem);
                }
            }
            return $formBuilder->getForm();
        }else {
            return $this->formFactory->create($this->getOption('form'), $entity);
        }
    }

    public function bindRequest(Request $request){
        if($request->get('id')){
            $entity = $this->getConfigurator()->getRepository()->find($request->get('id'));
        }else{
            $entity = $this->getConfigurator()->newInstance();
        }

        $this->form = $this->generateForm($entity);
        $this->form->handleRequest($request);
        if ('POST' == $request->getMethod() and $this->form->isSubmitted()) {
            if ($this->form->isValid()) {
                $em = $this->getConfigurator()->getEntityManager();
                $em->persist($entity);
                $em->flush();
                if($request->get('id')){
                    $response = new SuccessComponentResponse($this->trans('lego.form.success.edit'));
                } else {
                    $response = new SuccessComponentResponse($this->trans('lego.form.success.add'));
                    $this->resetForm();
                }
                $response->setRedirect($this->getConfigurator()->getPathRoute('index'), $this->getConfigurator()->getPathParameters());
                return $response;
            } else {
                return new ErrorComponentResponse('lego.form.error');
            }
        }
    }

    private function resetForm(){
        $this->form = $this->generateForm($this->getConfigurator()->newInstance());
    }

    public function getTitle(){
        return $this->getOption('title', $this->trans('lego.title.form'));
    }





}
