<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="{{ url('test') }}" enctype="multipart/form-data">
        @csrf
        <label for="name">name</label>
        <input type="text" name="name" id="name">
        <label for="email">email</label>
        <input type="text" name="email" id="email">
        <label for="password">password</label>
        <input type="password" name="password" id="password">
        <label for="password_confirmation">password_confirmation</label>
        <input type="password" name="password_confirmation" id="password_confirmation">
        <label for="picture">picture</label>
        <input type="file" name="picture" id="picture">
        <input type="gender" name="gender" id="gender" value="male">
        <input type="phone_number" name="phone_number" id="phone_number" value="0555555555">
        <input type="speciality" name="speciality" id="speciality" value="dentist">
        <input type="formations" name="formations" id="formations" value="formations+">
        <input type="type_consultation" name="type_consultation" id="type_consultation" value="all">
        <input type="city" name="city" id="city" value="sba">
        <input type="street" name="street" id="street" value="street22">
        <input type="localisation" name="localisation" id="localisation" value="22">
        <button type="submit">submit</button>
    </form>
</html>
