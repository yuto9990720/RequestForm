<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        $categories=Category::all();
        return view('contacts.index', compact('categories'));
    }

    public function confirm(ContactRequest $request)
    {
        $contact=$request->all();

         $contact['tel'] = $request->tel1 . $request->tel2 . $request->tel3;

         $category=Category::find($request->category_id);

         return view('contacts.confirm', compact('contact' , 'category'));
    }

    public function store(Request $request)
    {
        Contact::create(
            [
                'category_id' => $request->category_id,
                'last_name'   => $request->last_name,
                'first_name'  => $request->first_name,
                'gender'      => $request->gender,
                'email'       => $request->email,
                'tel'         => $request->tel,
                'address'     => $request->address,
                'building'    => $request->building,
                'detail'      => $request->detail,
            ]
        );

        return view('contacts.thanks');
    }

    public function thanks()
    {
        return view('contact.thanks');
    }
}
