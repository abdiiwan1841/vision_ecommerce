<?php

namespace App\Http\Controllers\Frontend;

use Session;
use App\Company;
use App\ContactUS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactpageController extends Controller
{
    public function index(){
        return view('frontend.contact.index');
    }

    public function contactus(Request $request){
        $this->validate($request,[
            'name' => 'required|max: 30',
            'email' => 'required|email|max: 30',
            'phone' => 'required|max: 25',
            'address' => 'required|max: 150',
            'message' => 'required',
        ]);

        $company = Company::first();
        $companymail = $company->email;
        $tomail = $request->email;
        $username = $request->name;
        ContactUS::create($request->all());

        Mail::send('email.contactmail',
            array(
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'user_message' => $request->message,
            ), 
        function($message)  use($companymail,$tomail,$username){
            $message->from($companymail);
            $message->to($tomail, $username)->subject('Thanks For Contacting Us');
        });

        Session::flash('success','Form Submitted Successfully');
        return redirect()->back();
    }
}
