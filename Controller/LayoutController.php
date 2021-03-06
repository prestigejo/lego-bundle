<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Idk\LegoBundle\Controller;


use Idk\LegoBundle\Service\LegoFooterInterface;
use Idk\LegoBundle\Service\LegoHeaderInterface;
use Idk\LegoBundle\Service\LegoMenuInterface;
use Idk\LegoBundle\Service\LegoRightInterface;
use Idk\LegoBundle\Service\Tag\RightBarChain;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The layout controller
 * @Route("/admin/layout")
 */
class LayoutController extends AbstractController
{

    public function menu(LegoMenuInterface $menu){
        return $this->render($menu->getTemplate(), ['menu' => $menu]);
    }

    public function header(LegoHeaderInterface $header){
        return $this->render($header->getTemplate(), ['header' => $header]);
    }

    public function footer(LegoFooterInterface $footer){
        return $this->render($footer->getTemplate(), ['footer' => $footer]);
    }

    public function right(LegoRightInterface $right){
        return $this->render($right->getTemplate(), ['right' => $right]);
    }

    
}
