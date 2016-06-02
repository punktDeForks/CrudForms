<?php
namespace Sandstorm\CrudForms\ViewHelpers\Widget\Controller;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryResultInterface;
use TYPO3\Fluid\Core\Widget\AbstractWidgetController;

class FilterController extends AbstractWidgetController
{

    /**
     * @var QueryResultInterface
     */
    protected $objects;

    /**
     * @var string
     */
    protected $filterProperty;

    /**
     * @return void
     */
    protected function initializeAction()
    {
        $this->objects = $this->widgetConfiguration['objects'];
        $this->filterProperty = $this->widgetConfiguration['filterProperty'];
    }

    /**
     * @param string $filterValue
     */
    public function indexAction($filterValue = '')
    {
        $query = clone $this->objects->getQuery();
        $constraintSoFar = $query->getConstraint();

        $filterConstraint = $query->like($this->filterProperty, trim($filterValue) . '%');

        if ($constraintSoFar) {
            $query->matching($query->logicalAnd($constraintSoFar, $filterConstraint));
        } else {
            $query->matching($filterConstraint);
        }

        $modifiedObjects = $query->execute();

        $this->view->assign('contentArguments', array(
            $this->widgetConfiguration['as'] => $modifiedObjects
        ));
        $this->view->assign('filterValue', $filterValue);
        $this->view->assign('filterPlaceholder', $this->widgetConfiguration['filterPlaceholder']);
    }
}
