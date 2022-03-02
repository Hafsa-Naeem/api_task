<?php

class TaskController
{
    public function __construct(private TaskGateway $gateway)
    {
    }

    public function processRequest($data): void
    {
        $this->gateway->create($data);
    }

    public function processFilter($values)
    {
        foreach ($values as $key => $value) {
            if ($value == "") {
                unset($values[$key]);
            }
        }
        $result = $this->gateway->search($values);
        return ($result);
    }

    public function getAllData()
    {
        $data = $this->gateway->getAll();
        return ($data);
    }

    public function processFilterLimit($initial_page, $limit, $formData)
    {
        foreach ($formData as $key => $value) {
            if ($value == "") {
                unset($formData[$key]);
            }
        }
        $result = $this->gateway->search_limit($initial_page, $limit, $formData);
        return ($result);
    }

    public function getLimitedData($initial_page, $limit)
    {
        $data = $this->gateway->getLimitedData($initial_page, $limit);
        return ($data);
    }

}
