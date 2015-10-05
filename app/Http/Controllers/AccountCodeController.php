<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\AccountCode;

class AccountCodeController extends Controller
{	

    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
    	$accountcodes = AccountCode::all();
    	return view('accountcodes', ['accountcodes' => $accountcodes]);
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {   
        $this->validate($request, [
            'name' => 'required|max:200',
            'accountcode' => 'required|unique:account_codes'
        ]);

    	AccountCode::create($request->all());
    	return redirect('accountcodes');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {   
        $account = AccountCode::find($id);
        $accountcodes = AccountCode::all();

        $data = [
            'account' => $account,
            'accountcodes' => $accountcodes
        ];

        return view('accountcode_edit', $data);
    }
    
    /**
     * [update description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {   
        $this->validate($request, [
            'name' => 'required|max:200'
        ]);

        $code = AccountCode::find($id);
        $code->name = $request->input('name');
        $code->save();

        return redirect('accountcodes');
    }

    /**
     * [destroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        AccountCode::destroy($id);
        return redirect('accountcodes');
    }
}
