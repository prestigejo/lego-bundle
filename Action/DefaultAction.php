<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);
namespace Idk\LegoBundle\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DefaultAction extends AbstractAction
{

    public function __invoke(Request $request): Response
    {
        $configurator = $this->getConfigurator($request);
        $this->denyAccessUnlessGranted($configurator->getEntityName(), $request->get('suffix_route'));
        $response = $this->comunicateComponents($configurator, $request);
        if($response) return $response;
        return new Response($this->renderView($configurator->getDefaultTemplate(), [ 'configurator' => $configurator, 'suffixRoute'=> $request->get('suffix_route') ]));
    }

}