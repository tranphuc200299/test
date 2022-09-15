<?php

namespace Modules\Customer\Services;

use Carbon\Carbon;
use Core\Services\BaseService;
use League\Csv\Writer;
use Modules\Customer\Repositories\CustomerRepository;
use Core\Constants\AppConst;

class CustomerService extends BaseService
{
    public function __construct(CustomerRepository $repository)
    {
        $this->mainRepository = $repository;
    }

    public function getAllCustomer($options = [], $limit = AppConst::PAGE_LIMIT_DEFAULT)
    {
        $options['limit'] = $limit;
        $this->makeBuilder($options);
        $this->builder
            ->when($this->filter->has('age_start'), function ($query) {
                $query->where('age', '>=', $this->filter->get('age_start'));
            })->when($this->filter->has('age_end'), function ($query) {
                $query->where('age', '<=', $this->filter->get('age_end'));
            })->when($this->filter->has('id'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', 'LIKE', cxl_replaceStringID($this->filter->get('id')) . "%");
                    $q->orWhere('id', 'LIKE', "%" . ($this->filter->get('id')) . "%");
                });
            })->when($this->filter->has('gender'), function ($query) {
                $query->where('gender', $this->filter->get('gender'));
            })->orderBy('id', 'ASC');
        $this->cleanFilterBuilder(['id', 'age_start', 'age_end', 'gender']);

        return $this->endFilter();
    }

    public function deleteMultiCustomer($listId)
    {
        return $this->mainRepository->deleteMultiRecord($listId);
    }

    public function deleteAll()
    {
        return $this->mainRepository->deleteAll();
    }

    public function export()
    {
        $customers = $this->getAllCustomer([], false);
        $csv = Writer::createFromFileObject(new \SplTempFileObject);
        $csv->setOutputBOM(Writer::BOM_UTF8);
        $csv->insertOne([
            '項番',
            'ID',
            '性別',
            '年齢',
        ]);

        foreach ($customers as $k => $customer) {
            $csv->insertOne([
                $k +1,
                'ID' . $customer->id ,
                $customer->gender == 'Male' ? '男性' : '女性',
                $customer->age
            ]);
        }

        $date = Carbon::now()->format('Ymd_His');
        $fileName = '集計一覧_' . $date . '_.csv';

        $csv->output($fileName);
    }

    public function getByListId($lisId)
    {
        return $this->mainRepository->getByListId($lisId);
    }
}
