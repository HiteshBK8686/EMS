<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Hauper - EMS</title>
<link rel="icon" href="./favicon.ico">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">
<link rel="stylesheet" href="./fonticon/fonticon.css">
<link rel="stylesheet" href="./splash-screen.css">

@vite('resources/css/app.css')



<!--begin::Theme mode setup on page load-->
<script>
    if (document.documentElement) {
        var defaultThemeMode = "system";

        // var name = document.body.getAttribute("data-kt-name");
        // var themeMode = localStorage.getItem("kt_" + (name ? name + "_" : "") + "theme_mode_value");
        let themeMode='';
        if (themeMode === null) {
            if (defaultThemeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            } else {
                themeMode = defaultThemeMode;
            }
        }
        document.documentElement.setAttribute("data-theme", themeMode);
    }
</script>
<!--end::Theme mode setup on page load-->


<div id="app"></div>
<!--begin::Loading markup-->
<div class="splash-screen">
    <img src="./media/logos/default-small.svg" alt="EMS logo">
    <span>Loading ...</span>
</div>
<!--end::Loading markup-->
<script>
    @auth
      window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
    @else
      window.Permissions = [];
    @endauth
  </script>
@vite('resources/ts/app.ts')
