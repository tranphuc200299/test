<?php

namespace Modules\Customer\Http\Controllers\Web;

use Core\Facades\Breadcrumb\Breadcrumb;
use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\Services\CustomerService;
use Illuminate\Support\Facades\Http;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;

    }

    public function index()
    {
        Breadcrumb::push(trans('customer::text.customer management'), route('cp.customers.index'));
        $assign['list'] = $this->customerService->getAllCustomer([]);
        if ($assign['list']->currentPage() > $assign['list']->lastPage())
            return redirect()->route('cp.customers.index', ['page' => $assign['list']->lastPage()]);

        return view('customer::customer.index', $assign);
    }

    public function destroy(Request $request)
    {

        if ($request->id) {
            $check = $this->customerService->getByListId($request->id);
            $this->customerService->deleteMultiCustomer($request->id);
            try {
                $response = Http::get(config('api.url_ai') . 'reload-database' );
            }catch (\Exception $e) {

            }
            return response()->json([
                'code' => 200,
                'message' => (count($check) > 0) ? 'レコードが正常に削除されました。' : 'レコードが削除されました。',
                'count' => count($check)
            ]);
        }

        return false;
    }

    public function deleteAll(Request $request)
    {
        $this->customerService->deleteAll();
        try {
            $response = Http::get(config('api.url_ai') . 'reload-database' );
        }catch (\Exception $e) {

        }
        return redirect()->route('cp.customers.index')->with('fail', trans('core::message.notify.delete success'));
    }

    public function export()
    {
        $this->customerService->export();
        die;
    }

}
