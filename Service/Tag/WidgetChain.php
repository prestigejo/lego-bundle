<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Idk\LegoBundle\Service\Tag;


use Symfony\Component\HttpFoundation\Session\Session;

class WidgetChain
{
    private $widgets = [];
    private $session;

    const SESSION_STORAGE = 'lego.widget';

    public function __construct(iterable $widgets, Session $session)
    {

        foreach($widgets as $widget){
            $this->widgets[$widget->getId()] = $widget;
        }
        $this->session = $session;
    }

    public function addWidget($widget)
    {
        $this->widgets[] = $widget;
    }

    public function get(string $id){
        return $this->widgets[$id];
    }


    public function getWidgets():iterable{
        return $this->widgets;
    }

    public function getTemplate(){
        return '@IdkLego/Widget/widget.html.twig';
    }

    public function getWidgetsTemplate(){
        return '@IdkLego/Widget/widgets.html.twig';
    }

    public function getWidgetsListTemplate(){
        return '@IdkLego/Widget/list.html.twig';
    }

    public function saveInSession($order){
        $this->session->set(self::SESSION_STORAGE, $order);
    }

    public function getOrder(){
        return $this->session->get(self::SESSION_STORAGE) ?? [];
    }


    public function getUseWidgets(){
        $useWidgets = [];
        foreach($this->getOrder() as $id){
            $useWidgets[] = $this->widgets[$id];
        }
        return $useWidgets;
    }

    public function getNoUseWidgets(){
        $noUseWidget = [];
        foreach($this->getWidgets() as $id => $widget){
            if(!in_array($id,$this->getOrder())){
                $noUseWidget[] = $widget;
            }
        }
        return $noUseWidget;
    }

    public function getCurrentWidgetId(){
        return $this->getWidgets();
    }

    public function saveWidget($request){
        $order = $request->request->get('sort');
        if($order == null) $order = [];
    }

}
