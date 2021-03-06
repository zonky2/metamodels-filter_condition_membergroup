<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package    MetaModels
 * @subpackage FilterConditionMemberGroup
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\Filter\Setting\Condition;

use MetaModels\Filter\IFilter;
use MetaModels\Filter\Setting\WithChildren;
use Contao\FrontendUser;

/**
 * This filter condition generates a "AND" condition from all child filter settings.
 * The generated rule will only return ids that are mentioned in ALL child rules.
 *
 */
class ConditionMemberGroup extends WithChildren
{
    /**
     * {@inheritdoc}
     */
    public function prepareRules(IFilter $objFilter, $arrFilterUrl)
    {
        $member = FrontendUser::getInstance();

        if ($this->get('member_group') && $member->isMemberOf($this->get('member_group')) && !$this->get('no_member')) {
            foreach ($this->arrChildren as $objChildSetting) {
                $objChildSetting->prepareRules($objFilter, $arrFilterUrl);
            }
        }

        if ($this->get('no_member') && !FE_USER_LOGGED_IN) {
            foreach ($this->arrChildren as $objChildSetting) {
                $objChildSetting->prepareRules($objFilter, $arrFilterUrl);
            }
        }
    }
}
