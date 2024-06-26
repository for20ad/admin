<style>
*{
    transition: all 0.6s;
}

html {
    height: 100%;
}

body {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    color: #888;
    margin: 0;
}

#main {
    display: table;
    width: 100%;
    height: 100vh;
    text-align: center;
}

.fof {
    display: table-cell;
    vertical-align: middle;
}

.fof h1 {
    font-size: 20px;
    display: inline-block;
    padding-right: 12px;
    animation: type .5s alternate infinite;
}

@keyframes type {
    from{box-shadow: inset -3px 0px 0px #888;}
    to{box-shadow: inset -3px 0px 0px transparent;}
}
</style>

<script>
if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
}
</script>

<div id="main">
    <div class="fof">
        <h1>404 Page Not Found</h1>
    </div>
</div>

