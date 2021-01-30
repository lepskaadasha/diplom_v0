<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ТКиОК</title>

    <link rel="shortcut icon" href="https://img.icons8.com/plasticine/100/000000/saving-book.png" type="image/png">

    <!-- bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous" />

    <!-- основной CSS -->
    <link href="app/assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="app/assets/css/admin.css">
</head>
<body>

<!-- navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="index.php">
  <img style="width: 25px; height: 25px" src="https://img.icons8.com/plasticine/100/000000/saving-book.png" alt="">        
  ТКиОК
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
          <a class="nav-item nav-link" href="index.php" id='home'>Домашняя страница</a>
          <a class="nav-item nav-link" href="admin.php" id='admin'>Админ панель</a>
          <a class="nav-item nav-link" href="update_account.php" id='update_account'>Учетная запись</a>
          <a class="nav-item nav-link" id='logout'>Выход</a>
      </div>
  </div>
</nav>
<!-- /navbar -->


<!-- здесь будет выводиться наше приложение -->
<div class="admin-menu">
    <div class="admin-item"><a href="admin.php">Темы</a></div>
    <div class="admin-item"><a href="section.php">Разделы</a></div>
    <div class="admin-item"><a href="user.php">Пользователи</a></div>
</div>

<div id="response"></div>

<div id="app">

</div>

<!-- jQuery -->
<script src="app/assets/js/jquery-3.5.1.min.js"></script>

<!-- jQuery & Bootstrap 4 JavaScript libraries -->
<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- для всплывающих окон -->
<script src="app/assets/js/bootbox.min.js"></script>

<!-- основной файл скриптов -->

<script src="app/app.js"></script>

<!-- themes scripts -->
<script src="app/theme/read-themes.js"></script>
<script src="app/theme/create-theme.js"></script>
<script src="app/theme/read-one-theme.js"></script>
<script src="app/theme/update-theme.js"></script>
<script src="app/theme/delete-theme.js"></script>

<!-- themes scripts -->
<script src="app/theme/themes.js"></script>
<script src="app/theme/search-themes.js"></script>

<style>
    .selected {
        font-weight: 600;
        color: #007BFF;
        border: 1px  #007BFF solid; 
        border-radius: 50%;
    }
</style>
<script>
    $(document).ready(()=>{ 

        createMenu();
        function createMenu() {
        let html = `<div class="navbar-nav">
            <a class="nav-item nav-link" href="index.php" id='home'>Домашняя страница</a>`;

            var isAdmin = getCookie('isAdmin');
            if (isAdmin == 'true') {
                html += `<a class="nav-item nav-link" href="admin.php" id='admin'>Админ панель</a>`
            }
            html +=`<a class="nav-item nav-link" href="update_account.php" id='update_account'>Учетная запись</a>
                <a class="nav-item nav-link" href="login.php" id='logout'>Выход</a>
            </div>`
            $('.CreateMenu').html(html);
        }

        var jwt = getCookie('jwt'); 
        if (jwt) {
            showThemesFirstPage();
        } else {
            changePageTitle("");
            $('#app').html("<div class='alert alert-danger'>Пожалуйста, авторизуйтесь как администратор, чтобы получить доступ к странице учетной записи. <a href='login.php'>Войти</a></div>");
        }

        function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' '){
                c = c.substring(1);
            }

            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
        }

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        $.fn.serializeObject = function(){

            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
            };

        function clearResponse(){
            $('#response').html('');
        }

        $(document).on('click', '#logout', ()=> { 
            setCookie("jwt", "", 1);
            setCookie("isAdmin", "", 1);
            document.location = 'login.php'; 
        });
    });
</script>

</body>
</html>