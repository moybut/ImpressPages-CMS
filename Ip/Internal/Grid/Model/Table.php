<?php
/**
 * @package   ImpressPages
 */

namespace Ip\Internal\Grid\Model;


class Table extends \Ip\Internal\Grid\Model
{

    /**
     * @var Config
     */
    protected $config = null;

    public function __construct($config)
    {
        $this->config = new Config($config);

        $this->actions = new Actions($this->config);
    }

    public function handleMethod(\Ip\Request $request)
    {
        $request = ipRequest()->getRequest();

        if (empty($request['method'])) {
            throw new \Ip\Exception('Missing request data');
        }
        $method = $request['method'];

        if (in_array($method, array('update', 'insert', 'delete', 'move'))) {
            ipRequest()->mustBePost();
        }

        if (in_array($method, array('update', 'insert'))) {
            $data = ipRequest()->getPost();
            $params = $data;
        } else {
            $data = ipRequest()->getRequest();
            $params = empty($data['params']) ? array() : $data['params'];
        }


        if (empty($data['hash'])) {
            $data['hash'] = '';
        }
        $hash = $data['hash'];

        $statusVariables = Status::parse($hash);


        if ($this->config->preventAction()) {
            $preventReason = call_user_func($this->config->preventAction(), $method, $params, $statusVariables);
            if ($preventReason) {
                return array(
                    Commands::showMessage($preventReason)
                );
            }
        }

        switch ($method) {
            case 'init':
                return $this->init($statusVariables);
                break;
            case 'page':
                return $this->page($params, $statusVariables);
                break;
            case 'delete':
                return $this->delete($params, $statusVariables);
                break;
            case 'updateForm':
                return $this->updateForm($params, $statusVariables)->render();
                break;
            case 'update':
                return $this->update($params, $statusVariables);
                break;
            case 'move':
                return $this->move($params, $statusVariables);
                break;
        }
    }

    protected function init($statusVariables)
    {
        $display = new Display($this->config);
        $commands = array();
        $html = $display->fullHtml($statusVariables);
        $commands[] = Commands::setHtml($html);
        return $commands;
    }

    protected function page($params, $statusVariables)
    {
        if (empty($params['page'])) {
            throw new \Ip\Exception('Missing parameters');
        }

        $statusVariables['page'] = $params['page'];
        $commands = array();
        $commands[] = Commands::setHash(Status::build($statusVariables));
        $display = new Display($this->config);
        $html = $display->fullHtml($statusVariables);
        $commands[] = Commands::setHtml($html);
        return $commands;
    }

    protected function delete($params, $statusVariables)
    {
        if (empty($params['id'])) {
            throw new \Ip\Exception('Missing parameters');
        }

        if ($this->config->beforeDelete()) {
            call_user_func($this->config->beforeDelete(), $params['id']);
        }

        try {
            $actions = new Actions($this->config);
            $actions->delete($params['id']);
            $display = new Display($this->config);
            $html = $display->fullHtml($statusVariables);
            $commands[] = Commands::setHtml($html);
            return $commands;
        } catch (\Exception $e) {
            $commands[] = Commands::showMessage($e->getMessage());
            return $commands;
        }

        if ($this->config->afterDelete()) {
            call_user_func($this->config->afterDelete(), $params['id']);
        }
    }

    protected function updateForm($params, $statusVariables)
    {
        $display = new Display($this->config);
        $updateForm = $display->updateForm($params['id']);
        return $updateForm;
    }

    protected function update($data, $statusVariables)
    {
        if (empty($data['recordId'])) {
            throw new \Ip\Exception('Missing parameters');
        }
        $recordId = $data['recordId'];
        $display = new Display($this->config);
        $updateForm = $display->updateForm($recordId);


        $errors = $updateForm->validate($data);

        if ($errors) {
            $data = array(
                'error' => 1,
                'errors' => $errors
            );
        } else {
            $newData = $updateForm->filterValues($data);

            if ($this->config->beforeUpdate()) {
                call_user_func($this->config->beforeUpdate(), $recordId, $newData);
            }

            $actions = new Actions($this->config);
            $actions->update($recordId, $newData);

            if ($this->config->afterUpdate()) {
                call_user_func($this->config->afterUpdate(), $recordId, $newData);
            }

            $display = new Display($this->config);
            $html = $display->fullHtml($statusVariables);
            $commands[] = Commands::setHtml($html);

            $data = array(
                'error' => 0,
                'commands' => $commands
            );
        }

        return $data;
    }

    protected function move($params, $statusVariables)
    {
        if (empty($params['id']) || empty($params['targetId']) || empty($params['beforeOrAfter'])) {
            throw new \Ip\Exception('Missing parameters');
        }

        if ($this->config->beforeMove()) {
            call_user_func($this->config->beforeMove(), $params['id']);
        }

        $id = $params['id'];
        $targetId = $params['targetId'];
        $beforeOrAfter = $params['beforeOrAfter'];

        $actions = new Actions($this->config);
        $actions->move($id, $targetId, $beforeOrAfter);
        $display = new Display($this->config);
        $html = $display->fullHtml($statusVariables);
        $commands[] = Commands::setHtml($html);
        return $commands;

        if ($this->config->afterMove()) {
            call_user_func($this->config->afterMove(), $params['id']);
        }
    }

}