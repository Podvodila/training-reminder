<?php

namespace App\Services\Telegram\Commands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\CallbackQuery;

abstract class CustomSystemCommand extends SystemCommand
{
    const AVAILABLE_QUERY_PARAMETERS = [];

    protected $queryData = [];
    /**
     * @var CallbackQuery
     */
    protected $query;

    public function __construct()
    {
        parent::__construct(...func_get_args());
        $this->setQueryData();
    }

    protected function getQueryData($param)
    {
        return $this->queryData[$param];
    }

    private function setQueryData()
    {
        $this->query = $this->getCallbackQuery();
        if (!$this->query) {
            return;
        }
        $queryData = $this->query->getData();
        $queryData = (preg_match('[@]', $queryData)) ? explode('@', $queryData)[1] : '';
        $queryData = explode(' ', $queryData);

        foreach (static::AVAILABLE_QUERY_PARAMETERS as $index => $name) {
            $this->queryData[$index] = $queryData[$index];
        }
    }
}
