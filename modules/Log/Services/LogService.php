<?php

namespace Modules\Log\Services;

use Carbon\Carbon;
use Core\Services\BaseService;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Modules\Log\Repositories\LogRepository;
use Core\Constants\AppConst;
use ZipArchive;

class LogService extends BaseService
{
    public function __construct(LogRepository $repository)
    {
        $this->mainRepository = $repository;
    }

    public function checkDataTime($request) : bool
    {
        return array_key_exists('start_date', $request);
    }

    public function getAll($request, $options = [], $limit = AppConst::PAGE_LIMIT_DEFAULT)
    {
        $options['limit'] = $limit;
        $this->makeBuilder($options);
        $check = $this->checkDataTime($request->all());
        $now = Carbon::now();

        $this->builder
            ->when($check === false, function ($q) use ($now) {
                $q->whereDate('created_at', $now);
            }, function ($queryDate) {
                $queryDate->when($this->filter->has('start_date'), function ($query) {
                    $query->whereDate('created_at', '>=', $this->filter->get('start_date'));
                })->when($this->filter->has('end_date'), function ($query) {
                    $query->whereDate('created_at', '<=', $this->filter->get('end_date'));
                })->when($this->filter->has('start_time'), function ($query) {
                    $query->whereTime('created_at', '>=', $this->filter->get('start_time'));
                })->when($this->filter->has('end_time'), function ($query) {
                    $query->whereTime('created_at', '<=', $this->filter->get('end_time'));
                })->when($this->filter->has('age_start'), function ($query) {
                    $query->where('age', '>=', $this->filter->get('age_start'));
                })->when($this->filter->has('age_end'), function ($query) {
                    $query->where('age', '<=', $this->filter->get('age_end'));
                })->when($this->filter->has('gender'), function ($query) {
                    $query->where('gender', $this->filter->get('gender'));
                })->when($this->filter->has('id'), function ($q) {
                        $q->where(function ($queryId) {
                            $queryId->where('user_id', 'LIKE', cxl_replaceStringID($this->filter->get('id')) . "%");
                            $queryId->orWhere('user_id', 'LIKE', "%" . ($this->filter->get('id')) . "%");
                        });
                    });
            })->orderByDesc('created_at');

        $this->cleanFilterBuilder(['id', 'age_start', 'age_end', 'gender']);

        return $this->endFilter();
    }

    public function deleteMultiRecord($listId)
    {
        return $this->mainRepository->deleteMultiRecord($listId);
    }

    public function deleteAll()
    {
        return $this->mainRepository->deleteAll();
    }

    public function export($request)
    {
        $logs = $this->getAll($request,['with_load' => 'customer'], false);

        $csv = Writer::createFromFileObject(new \SplTempFileObject);
        $csv->setOutputBOM(Writer::BOM_UTF8);

        $csv->insertOne([
            '項番',
            'ID',
            '画像',
            '性別',
            '年齢',
            'チェックイン日付',
            'チェックイン時刻'
        ]);

        foreach ($logs as $k => $log) {
            $csv->insertOne([
                $k + 1,
                'ID' . $log->customer->id,
                env('URL_AI') . $log->face_image_url,
                $log->gender == 'Male' ? '男性' : '女性',
                $log->age,
                Carbon::parse($log->created_at)->format('Y/m/d'),
                Carbon::parse($log->created_at)->format('H:i:s')
            ]);
        }
        $date = Carbon::now()->format('Ymd_His');
        $fileName = '集計一覧_' . $date . '_.csv';

        $csv->output($fileName);
    }

    public function download($request): \Illuminate\Http\JsonResponse
    {
        $logs = $this->getAll($request, [], false);
        $now = Carbon::now()->timestamp;
        $imageError = [];
        foreach ($logs as $log) {
            $url = env('URL_AI') . $log->face_image_url;
            try {
                $contents = file_get_contents($url);
                $name = $now . '/' . substr($url, strrpos($url, '/') + 1);
                Storage::put($name, $contents);
            } catch (\Exception $e) {
                $imageError[] = $log->face_image_url;
            }
        };

        $zip = new ZipArchive;
        $fileName = 'storage/download/' . Auth::id() . '__' . $now . 'download.zip';
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
            $files = File::files(public_path('storage/' . $now));
            foreach ($files as $key => $value) {
                $relativeName = basename($value);
                $zip->addFile($value, $relativeName);
            }
            $zip->close();

        }

        Storage::deleteDirectory($now);
        return response()->json([
            'code' => 200,
            'file_name' => Storage::url(str_replace('storage/', '', $fileName)),
            'image_erorr' => $imageError,
        ]);


    }

    public function getByListId($lisId)
    {
        return $this->mainRepository->getByListId($lisId);
    }

    public function deleteImages()
    {
        $file = new Filesystem;
        $file->cleanDirectory('storage/app/public/download');
    }
}
