<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\AccountCode;

class AccountCodeController extends Controller
{	

    /**
     * Display all account codes.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$accountcodes = AccountCode::orderBy('name', 'asc')->get();
    	return view('accountcode.show', ['accountcodes' => $accountcodes]);
    }

    /**
     * Save a newly created account code.
     * @param  Request $request
     * @return \Illuminate\Http\Response
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
     * Edit the specified account code.
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {   
        $account = AccountCode::find($id);
        $accountcodes = AccountCode::orderBy('name', 'asc')->get();

        $data = [
            'account' => $account,
            'accountcodes' => $accountcodes
        ];

        return view('accountcode.edit', $data);
    }
    
    /**
     * Save modifications for specified account code.
     * @param  Request $request
     * @param  Int $id
     * @return \Illuminate\Http\Response
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
     * Delete the specified account code.
     * @param  Int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AccountCode::destroy($id);
        return redirect('accountcodes');
    }
}
