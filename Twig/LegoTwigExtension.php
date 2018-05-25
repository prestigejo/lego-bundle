<?php

namespace Idk\LegoBundle\Twig;


use Idk\LegoBundle\Service\EditInPlaceFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Idk\LegoBundle\Component\Component;
use Idk\LegoBundle\Annotation\Entity\Field;


class LegoTwigExtension extends \Twig_Extension
{
    private $editInPlaceFactory;


    public function __construct(EditInPlaceFactory $editInPlaceFactory){
        $this->editInPlaceFactory = $editInPlaceFactory;
    }


    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('render_field_value',array($this,'renderFieldValue'), array('is_safe' => array('html'),'needs_environment' => true)),
            new \Twig_SimpleFunction('get_class', array($this, 'getClass'))
        ];
    }



    public function getClass($object)
    {
        if(is_object($object)) {
            return (new \ReflectionClass($object))->getName();
        }
    }

    public function renderFieldValue(\Twig_Environment $env, Component $component, Field $field, $item)
    {
        $template = $env->loadTemplate("IdkLegoBundle:LegoTwigExtension:_field_value.html.twig");
        $configurator = $component->getConfigurator();
        $type = $configurator->getType($item,$field->getName());
        $value =  $field->getValue($component->getConfigurator(), $item);
        $editInPlaceType = null;
        if($field->isEditInPlace($item)) {
            $editInPlaceType = $this->editInPlaceFactory->getEditInPlaceType($type, $value, $field->getName());
        }
        if(!$component->getConfiguratorBuilder()->hasAccess(get_class($item),'edit')) $editInPlaceType = null;
        if(!$component->getConfiguratorBuilder()->hasAccess(get_class($item),'edit_in_place')) $editInPlaceType = null;
        return $template->render(array(
            'field'        => $field,
            'configurator'      => $component->getConfigurator(),
            'component' => $component,
            'item'     => $item,
            'stringValue' => $field->getStringValue($component->getConfigurator(), $item),
            'value' => $field->getValue($component->getConfigurator(), $item),
            'eipType' => $editInPlaceType,
            'path' => ($value)? $configurator->getPathByField($item,$field):null
        ));
    }


    public function getName()
    {
        return 'lego_twig_extension';
    }

}
