<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Form Submission</title>
</head>
<body>
    <h1>New Contact Form Submission</h1>
    <p><strong>Name:</strong> {{ $contactData->name }}</p>
    <p><strong>Email:</strong> {{ $contactData->email }}</p>
    <p><strong>Subject:</strong> {{ $contactData->subject }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $contactData->message }}</p>
</body>
</html>
