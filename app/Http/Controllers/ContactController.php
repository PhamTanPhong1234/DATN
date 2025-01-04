<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendContactForm(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Send the email
        Mail::to('your-email@example.com')->send(new ContactFormMail($request));

        // Redirect back with a success message
        return back()->with('success', 'Cảm ơn bạn! Tin nhắn của bạn đã được gửi.');
    }
}
