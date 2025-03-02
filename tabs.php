<style>
    .sidenav {
        height: 100%;
        width: 6rem;
        z-index: 1;
        overflow-x: hidden;
        padding-top: 60px;
    }

    .sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        color: #818181;
        display: block;
    }

    .sidenav a:hover {
        color: #f1f1f1;
    }

    .sidenav .closebtn {
        font-size: 36px;
        margin-left: 50px;
    }

    .sidenav .au_logo {
        width: 4rem;
        height: 4rem;
    }

    @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
    }
</style>

<div id="sidenav" class="sidenav d-flex flex-column fs-4 bg-blue">
    <div class="d-flex">
        <div class="d-flex">
            <img class="au_logo" src="./assets/img/au_logo.png" alt="">
            <p class="fs-2">TSMS</p>
        </div>
        <span style="font-size:30px;cursor:pointer" onclick="closeNav()">&#9776;</span>
    </div>

  <a href="#">About</a>
  <a href="#">Services</a>
  <a href="#">Clients</a>
  <a href="#">Contact</a>
</div>

<script>
function openNav() {
  document.getElementById("sidenav").style.width = "25rem";
}

function closeNav() {
  document.getElementById("sidenav").style.width = "6rem";
}
</script>