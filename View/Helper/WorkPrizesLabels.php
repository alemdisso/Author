<?php

class Author_View_Helper_WorkPrizesLabels extends Zend_View_Helper_Abstract
{
    public function workPrizesLabels($workId, Author_Collection_PrizeMapper $mapper)
    {
        $prizesIds = $mapper->getAllPrizesOfWork($workId);
        $prizesLabels = array();
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
            $prizesLabels[$prizeId] = array('id' => $prizeId, 'label' => $label);
        }

        return $prizesLabels;
    }
}

