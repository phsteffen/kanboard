<?php

namespace Kanboard\Action;

use Kanboard\Model\TaskLink;

/**
 * Set a category automatically according to a task link
 *
 * @package action
 * @author  Olivier Maridat
 * @author  Frederic Guillot
 */
class TaskAssignCategoryLink extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Assign automatically a category based on a link');
    }

    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(
            TaskLink::EVENT_CREATE_UPDATE,
        );
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array(
            'category_id' => t('Category'),
            'link_id' => t('Link type'),
        );
    }

    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return array(
            'task_id',
            'link_id',
        );
    }

    /**
     * Execute the action (change the category)
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $values = array(
            'id' => $data['task_id'],
            'category_id' => $this->getParam('category_id'),
        );

        return $this->taskModification->update($values);
    }

    /**
     * Check if the event data meet the action condition
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        if ($data['link_id'] == $this->getParam('link_id')) {
            $task = $this->taskFinder->getById($data['task_id']);
            return empty($task['category_id']);
        }

        return false;
    }
}
