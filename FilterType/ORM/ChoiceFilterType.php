<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Idk\LegoBundle\FilterType\ORM;

use Symfony\Component\HttpFoundation\Request;

/**
 * StringFilterType
 */
class ChoiceFilterType extends AbstractORMFilterType
{

    private $choices;
    private $multiple;
    /**
     * @param Request $request  The request
     * @param array   &$data    The data
     * @param string  $uniqueId The unique identifier
     */
    public function bindRequest(array &$data, $uniqueId)
    {
        $data['comparator'] = $this->getValueSession('filter_comparator_' . $uniqueId);
        $data['value']      = $this->getValueSession('filter_value_' . $uniqueId);
        return ($data['value'] != null);
    }

     /**
     * @param string $columnName The column name
     * @param string $alias      The alias
     */
    public function load($columnName, $config = array(), $alias = 'b')
    {
        parent::load($columnName, $config, $alias);
        $this->choices = $config['choices'];
        $this->multiple = (isset($config['multiple']))? $config['multiple']:true;
    }


    /**
     * @param array  $data     The data
     * @param string $uniqueId The unique identifier
     */
    public function apply(array $data, $uniqueId, $alias, $col)
    {   
        if (isset($data['value'])) {
            $qb = $this->queryBuilder;
            if($this->getMultiple()){
                $qb->andWhere($qb->expr()->in($alias.$col, ':var_' . $uniqueId));
            }else{
                $qb->andWhere($qb->expr()->eq($alias.$col, ':var_' . $uniqueId));
            }
            $qb->setParameter('var_' . $uniqueId, $data['value']);
        }
    }

    public function getChoices(){
        return $this->choices;
    }

    public function isSelected($data,$value){
        //print_r($data);
        //print_r($value);
        if(is_array($data['value'])){
            return in_array($value,$data['value']);
        }else{
            return ($data['value'] == $value);
        }
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return '@IdkLego/FilterType/choiceFilter.html.twig';
    }

    public function getMultiple(){
        return $this->multiple;
    }
}
