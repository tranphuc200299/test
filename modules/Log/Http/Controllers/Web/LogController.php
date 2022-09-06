<?php

namespace Modules\Log\Http\Controllers\Web;

use Core\Facades\Breadcrumb\Breadcrumb;
use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Log\Services\LogService;

class LogController extends Controller
{
    private $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;

    }

    public function index()
    {
        Breadcrumb::push('集計一覧', route('cp.logs.index'));
        $assign['list'] = $this->logService->getAll(['with_load' => 'customer']);

        return view('log::log.index', $assign);
    }

    public function destroy(Request $request)
    {
        if ($request->id) {
            $this->logService->deleteMultiRecord($request->id);
            return true;
        }

        return false;
    }

    public function deleteAll(Request $request)
    {
        $this->logService->deleteAll();
        return redirect()->route('cp.logs.index')->with('fail', trans('core::message.notify.delete success'));
    }

    public function export()
    {
        $this->logService->export();
        die;
    }

    public function download()
    {
        if($this->logService->download())
        {
            return $this->logService->download();
        }

        return redirect()->route('cp.logs.index')->with('error', 'Download false');
    }
}
