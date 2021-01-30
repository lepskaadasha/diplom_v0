<!doctype html>
<html lang="ru">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
 
        <title>ТКиОК</title>

        <link rel="shortcut icon" href="https://img.icons8.com/plasticine/100/000000/saving-book.png" type="image/png">
        
        
        <!-- Bootstrap 4 CSS and custom CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous" />
        <link rel="stylesheet" href="app/assets/css/home.css">
        <script src="app/assets/js/PDFObject-master/pdfobject.js"></script>

    </head>
<body>
 
<!-- navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="#">
  <img style="width: 25px; height: 25px" src="https://img.icons8.com/plasticine/100/000000/saving-book.png" alt="">        
  ТКиОК
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
  </button>
   <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
          <a class="nav-item nav-link" href="index.php" id='home'>Домашняя страница</a>
          <a class="nav-item nav-link" href="update_account.php" id='update_account'>Учетная запись</a>
          <a class="nav-item nav-link" id='logout'>Выход</a>
      </div>
  </div>
</nav>
<!-- /navbar -->

<div id="response"></div>

<div class="wrapper" style="display: none;">
    <nav class="sidebar">
        <ul class="sidebar-list" id="sidebarList">
                
            </li>
        </ul>
    </nav>
    <div class="content">
    <div id="pdf" class="pdfobject-container"></div>
    </div>
</div>
<!-- /container -->
<style>
.pdfobject-container { height: 100%; border: 1rem solid rgba(0,0,0,.1); }
</style>

<!-- jQuery & Bootstrap 4 JavaScript libraries -->
<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script> 
     $(document).ready(() => { 

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
            </div>`;
            $('#navbarNavAltMarkup').html(html);
        }

        var jwt = getCookie('jwt'); 
        if (jwt) {
            $('.wrapper').css('display', 'flex');
            var themes;
            $.ajax({
                url: "http://diplom/api/theme/read.php",
                type : "GET",
                contentType : 'application/json',
                success : function(result) {
                    result = result['records'];
                    sections = [...new Set(result.map(el=>el.section_name))];
                    var html = '';
                    var i = 0;
                    sections.forEach(element => {
                        html += `<ul class="sidebar-section" id="section${i}">
                    <div class="section-head" onclick="uncover('section${i++}')">${element}</div>`;

                          themes = result.filter((el)=>{
                              if (el.section_name == element) return el.name;
                          });
                          
                          themes.forEach(theme => {
                              html += `<li onclick="openTheme('${theme.filepatch}')"><input type="checkbox">${theme.name}</li>`;
                          });

                        html += `</ul>`;
                    });
                   $('#sidebarList').html(html);
                },
                error: function(xhr, resp, text) {
                    console.log(xhr, resp, text);
                }
            });
            

        } else {
            $('.wrapper').css('display', 'none');
            $('#response').html("<div style='padding-top: 65px' class='alert alert-danger'>Пожалуйста, авторизуйтесь, чтобы получить доступ к странице учетной записи. <a href='login.php'>Войти</a></div>");
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

    function uncover(id) {
        $('#'+id).toggleClass('uncover');
    }
    
    function openTheme(filepatch) {
        PDFObject.embed(filepatch, "#pdf");

    }

    

</script>

</body>
</html>