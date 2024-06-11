<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SendGrid\Mail\Mail;

class MailController extends Controller
{
    public function send(): JsonResponse
    {
        $email = new Mail();
        $email->setFrom(env('SENDGRID_FROM_ADDRESS'), "HR ADMIN");
        $email->setSubject("Welcome to HR ADMIN");
        $email->addTo("abahoallans@gmail.com");
        $email->addContent("text/plain", "An easy way to borrow and lend money to employees all over the world");
        $email->addContent(
            "text/html",
            "<strong>and easy to do anywhere, even with PHP</strong>"
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => ['message' => $e->getMessage(), 'code' => $response->statusCode()]]);
        }
        return response()->json(['success' => true, 'data' => ['message' => $response->body(), 'code' => $response->statusCode(),]]);
    }
}
