<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\base;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActionFilter extends Behavior
{
	/**
	 * @var array list of action IDs that this filter should apply to. If this property is not set,
	 * then the filter applies to all actions, unless they are listed in [[except]].
	 */
	public $only;
	/**
	 * @var array list of action IDs that this filter should not apply to.
	 */
	public $except = array();

	/**
	 * Declares event handlers for the [[owner]]'s events.
	 * @return array events (array keys) and the corresponding event handler methods (array values).
	 */
	public function events()
	{
		return array(
			'beforeAction' => 'beforeFilter',
			'afterAction' => 'afterFilter',
		);
	}

	/**
	 * @param ActionEvent $event
	 * @return boolean
	 */
	public function beforeFilter($event)
	{
		if ($this->isActive($event->action)) {
			$event->isValid = $this->beforeAction($event->action);
		}
		return $event->isValid;
	}

	/**
	 * @param ActionEvent $event
	 * @return boolean
	 */
	public function afterFilter($event)
	{
		if ($this->isActive($event->action)) {
			$this->afterAction($event->action);
		}
	}

	/**
	 * This method is invoked right before an action is to be executed (after all possible filters.)
	 * You may override this method to do last-minute preparation for the action.
	 * @param Action $action the action to be executed.
	 * @return boolean whether the action should continue to be executed.
	 */
	public function beforeAction($action)
	{
		return true;
	}

	/**
	 * This method is invoked right after an action is executed.
	 * You may override this method to do some postprocessing for the action.
	 * @param Action $action the action just executed.
	 */
	public function afterAction($action)
	{
	}

	/**
	 * Returns a value indicating whether the filer is active for the given action.
	 * @param Action $action the action being filtered
	 * @return boolean whether the filer is active for the given action.
	 */
	protected function isActive($action)
	{
		return !in_array($action->id, $this->except, true) && (empty($this->only) || in_array($action->id, $this->only, true));
	}
}
