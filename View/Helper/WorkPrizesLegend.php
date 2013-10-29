<?php

class Author_View_Helper_WorkPrizesLegend extends Zend_View_Helper_Abstract
{
    public function workPrizesLegend($workId, Author_Collection_PrizeMapper $mapper)
    {
        $prizesIds = $mapper->getAllPrizesOfWork($workId);
        $prizesLegend = array();
        foreach($prizesIds as $prizeId) {
            $loopPrizeObj = $mapper->findById($prizeId);
            $label = "";
            if ($loopPrizeObj->getYear()) {
                $label = $loopPrizeObj->getYear() . " - ";
            }
            $label .= $loopPrizeObj->getPrizeName();
            if ($loopPrizeObj->getInstitutionName()) {
                $label .= ", " . $loopPrizeObj->getInstitutionName();
            }
            if ($loopPrizeObj->getCategoryName()) {
                $label .= " (" . $loopPrizeObj->getCategoryName() . ")";
            }
            $prizesLegend[$prizeId] = array('id' => $prizeId, 'label' => $label);
        }

        return $prizesLegend;
    }
}

