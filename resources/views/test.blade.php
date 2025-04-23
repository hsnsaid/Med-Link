<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="/api/v1/users" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">      
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="password_confirmation">password_confirmation:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required><br><br>
        <label for="phoneNumber">Phone Number:</label>
        <input type="tel" id="phoneNumber" name="phoneNumber" required><br><br>
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required><br><br>
        <label for="sexe">Sexe:</label>
        <select id="sexe" name="sexe" required>
          <option value="">Select</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select><br><br>
        <label for="chronicDisease">Chronic Disease:</label>
        <input type="text" id="chronicDisease" name="chronicDisease"><br><br>
        <label for="groupage">Blood Group:</label>
        <input type="text" id="groupage" name="groupage" required><br><br>
        <button type="submit">Submit</button>
      </form>
      
</body>
</html>