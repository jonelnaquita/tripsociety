<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="../dist/css/adminlte.min.css?v=3.2.0">
</head>

<body class="hold-transition login-page bg-white">

    <div class="login-box" style="padding: 20px; display: flex; flex-direction: column; align-items: center;">
        <div class="login-logo text-center">
            <img src="../img/logo.png" width="100px;">
            <h1 class="font-weight-bold mt-1" style="color: #333; font-size: 28px;">Travel Spots</h1>
            <p style="font-size: 22px; line-height: 26px; color: #555; margin: 10px 0;">
                Explore Recommended <br> traveler spots in Batangas <br> near you, wherever you <br> may be.
            </p>
        </div>
    </div>

    <div class="position-absolute w-100 d-flex justify-content-center" style="bottom: 30px;">
        <a href="register.php" class="btn btn-primary text-white shadow-sm"
            style="width: 90%; max-width: 400px; font-size: 20px; border-radius: 50px; padding: 10px 0;">
            Get Started
        </a>
        <button type="button" class="btn btn-primary text-white shadow-sm" id="install-button"
            style="display:none;">Install App</button>
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('../service-worker.js')
                    .then(registration => {
                        console.log('Service Worker registered with scope:', registration.scope);
                    })
                    .catch(error => {
                        console.error('Service Worker registration failed:', error);
                    });
            });
        }
    </script>

    <script>
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Show the install button
            const installButton = document.getElementById('install-button');
            installButton.style.display = 'block';

            installButton.addEventListener('click', () => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(choiceResult => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            });
        });

    </script>

    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/adminlte.min.js?v=3.2.0"></script>
</body>


</html>