<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f4f8;
    }

    .login {
      background-color: #000;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .loginform {
      margin-top: 0;
      position: relative;
      border: 1px solid #ddd;
      background-color: #fff;
      max-width: 400px;
      padding: 0;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      border-radius: 20px;
    }

    .container:before,
    .container:after {
      display: table;
      content: " ";
    }

    .container:after {
      clear: both;
    }

    #owl-login {
      width: 116px;
      height: 92px;
      background-image: url(https://cdn.readme.io/public/img/login/face.png);
      position: absolute;
      top: -82px;
      left: 50%;
      margin-left: -58px;
    }

    .arm-up-right,
    .arm-up-left,
    .arm-down-left,
    .arm-down-right {
      position: absolute;
      transition: .3s ease-out;
    }

    .arm-up-right {
      width: 51px;
      height: 41px;
      background-image: url(https://cdn.readme.io/public/img/login/arm-up-right.png);
      bottom: 11px;
      right: 5px;
      transform: translateX(57px) scale(.8);
      transform-origin: 0 40px;
      opacity: 0;
    }

    .arm-up-left {
      width: 52px;
      height: 41px;
      background-image: url(https://cdn.readme.io/public/img/login/arm-up-left.png);
      bottom: 11px;
      left: -3px;
      transform: translateX(-34px) scale(.8);
      transform-origin: 0 40px;
      opacity: 0;
    }

    .arm-down-left {
      width: 43px;
      height: 25px;
      background-image: url(https://cdn.readme.io/public/img/login/arm-down-left.png);
      bottom: 2px;
      left: -34px;
    }

    .arm-down-right {
      width: 43px;
      height: 26px;
      background-image: url(https://cdn.readme.io/public/img/login/arm-down-right.png);
      bottom: 1px;
      right: -40px;
    }

    .pad {
      overflow: hidden;
      padding: 30px;
    }

    .controls {
      position: relative;
      margin-bottom: 20px;
    }

    .loginform label {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      font-size: 16px;
      color: rgba(0, 0, 0, .3);
    }

    .loginform input {
      padding: 10px 10px 10px 40px;
      height: auto;
      border: 1px solid #ccc;
      width: 100%;
      box-sizing: border-box;
      border-radius: 5px;
      background-repeat: no-repeat;
      background-position: 10px center;
    }

    .form-actions {
      border-top: 1px solid #e4e4e4;
      background-color: #f7f7f7;
      padding: 15px 30px;
      text-align: center;
      border-radius: 0 0 20px 20px;
    }

    #error-message {
      color: red;
      margin-top: 10px;
      text-align: center;
    }

    #owl-login.password .arm-down-left {
      transform: translateX(40px) scale(0) translateY(-10px);
      opacity: 0;
    }

    #owl-login.password .arm-up-left {
      transform: scale(1);
      opacity: 1;
    }

    #owl-login.password .arm-down-right {
      transform: translateX(-32px) scale(0) translateY(-8px);
      opacity: 0;
    }

    #owl-login.password .arm-up-right {
      transform: scale(1);
      opacity: 1;
    }

    .btn {
      background-color: #018ef5;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      transition: background-color .3s;
      border-radius: 5px;
    }

    .btn:hover {
      background-color: #0177d1;
    }

    .btn-link {
      color: #018ef5;
      text-decoration: none;
    }

    .btn-link:hover {
      text-decoration: underline;
    }

    .text-muted {
      color: #6c757d !important;
    }
  </style>
</head>

<body>
  <div class="login">
    <form id="loginForm" class="container loginform" method="POST">
      <div id="owl-login">
        <div class="eyes"></div>
        <div class="arm-up-right"></div>
        <div class="arm-up-left"></div>
        <div class="arm-down-left"></div>
        <div class="arm-down-right"></div>
      </div>
      <div class="pad">
        <div class="control-group">
          <div class="controls">
            <label for="email" class="control-label fa fa-envelope"></label>
            <input id="email" class="form-control" name="email" placeholder="Email" autocomplete="off">
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <label for="password" class="control-label fa fa-asterisk"></label>
            <input id="password" type="password" name="password" placeholder="Password" required="" autocomplete="off"
              class="form-control">
          </div>
          <button type="submit" class="btn btn-primary">Log In</button>
          <div id="error-message" style="display: none;">Usuario o contraseña incorrectos</div>
        </div>
      </div>
    </form>
  </div>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include './models/conexion.php';

    class Login
    {
      private $conn;

      public function __construct()
      {
        $db = new Conexion();
        $this->conn = $db->conectar();
      }

      public function verificarUsuario($email, $password)
      {
        $email = mysqli_real_escape_string($this->conn, $email);
        $password = mysqli_real_escape_string($this->conn, $password);

        $query = "SELECT * FROM usuarios WHERE usuario = '$email' AND contrasenia = '$password'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
          $user = mysqli_fetch_assoc($result);
          return $user;
        } else {
          return false;
        }
      }
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $login = new Login();
    $user = $login->verificarUsuario($email, $password);

    if ($user) {
      session_start();
      $_SESSION['loggedin'] = true;
      $_SESSION['email'] = $email;
      $_SESSION['rol'] = $user['rol'];
      if ($user['rol'] == "administrador") {
        include "../views/plantilla.php";
        echo '<script>window.location.reload()</script>';
      } else {
        include "../views/interfaces/GestionInOut.php";
        echo '<script>window.location.reload()</script>';

      }
    } else {
      echo '<script>document.getElementById("error-message").style.display = "block";</script>';
      echo '<script>document.getElementById("error-message").textContent = "Usuario o contraseña incorrectos";</script>';
    }
  }
  ?>
  
  <script>
    const $owlLogin = document.getElementById('owl-login');
    const $passwordInput = document.getElementById('password');

    $passwordInput.addEventListener('focus', () => {
      $owlLogin.classList.add('password');
    });

    $passwordInput.addEventListener('focusout', () => {
      $owlLogin.classList.remove('password');
    });
  </script>
</body>

</html>
