<?php


namespace App\Http\Controllers\Modules\CompanySettings;


use App\Helpers\Helper;
use App\Http\Controllers\ModuleController;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompanySettingsController extends ModuleController
{
    public function __construct()
    {
        parent::__construct();
        $this->setModuleName('companySettings');
    }
    public function index()
    {
        $company = Auth::user()->toArray()['company'];
        return $this->view('index',['data'=>$company]);
    }

    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required',
            'email' => 'required|string',
            'address' => 'required|string',
        ])->validate();

        $cdata = $request->except('_token', '_method');
        $old = Company::where('id', $cdata['id'])->first();
        if ($request->hasFile('logo')) {
            if(!empty($old['logo'])){
                $file = str_ireplace("storage/app/",'',$old['logo']);
                if(Storage::exists($file)){
                    Storage::delete($file);
                }
            }

            $cdata['logo'] = Helper::file_upload($request,'logo','company');
        }
        Company::where('id', $cdata['id'])->update($cdata);

        return redirect()->route($this->mRoute('home'))->with('success', 'Company Updated Successfully!');

    }

    protected function getModuleTable(): string
    {
        return 'company';
    }
}
