<?php
function logAktivitas($conn, $id_user, $aktivitas)
{
    $aktivitas = mysqli_real_escape_string($conn, $aktivitas);
    $query = "INSERT INTO log_aktivitas (id_user, aktivitas) VALUES ('$id_user', '$aktivitas')";
    mysqli_query($conn, $query);
}

function setFlash($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function displayFlash()
{
    if (isset($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $message = $_SESSION['flash']['message'];

        $class = ($type == 'success') ? 'alert-success' : 'alert-error';
        $icon = ($type == 'success') ? 'circle-check' : 'circle-xmark';

        echo "
        <style>
            @keyframes slideInFromLeft {
                0% {
                    transform: translateX(-120%);
                    opacity: 0;
                }
                100% {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutToTop {
                0% {
                    transform: translateY(0) scale(1);
                    opacity: 1;
                }
                100% {
                    transform: translateY(-100%) scale(0.9);
                    opacity: 0;
                }
            }
            
            @keyframes progressShrink {
                0% {
                    width: 100%;
                }
                100% {
                    width: 0%;
                }
            }
            
            .toast-notification {
                animation: slideInFromLeft 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
                position: relative;
                overflow: hidden;
            }
            
            .toast-notification.hiding {
                animation: slideOutToTop 0.4s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            }
            
            .toast-progress {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                background: currentColor;
                opacity: 0.4;
                animation: progressShrink 3s linear forwards;
            }
        </style>
        
        <div id='toast-flash' class='alert $class shadow-lg mb-4 text-sm font-semibold toast-notification'>
            <div class='flex items-center gap-2'>
                <i class='fa-solid fa-$icon text-lg'></i>
                <span>$message</span>
            </div>
            <button onclick='closeToast()' class='btn btn-ghost btn-xs btn-circle ml-auto'>
                <i class='fa-solid fa-xmark'></i>
            </button>
            <div class='toast-progress'></div>
        </div>
        
        <script>
            // Auto hide toast after 3 seconds
            const toastElement = document.getElementById('toast-flash');
            
            function closeToast() {
                if (toastElement) {
                    toastElement.classList.add('hiding');
                    setTimeout(() => {
                        toastElement.remove();
                    }, 400);
                }
            }
            
            // Auto close after 3 seconds
            setTimeout(() => {
                closeToast();
            }, 3000);
        </script>
        ";
        unset($_SESSION['flash']);
    }
}

function checkLogin()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit();
    }

    // Auto logout after 30 mins of inactivity
    $timeout = 1800; // 30 minutes
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        session_unset();
        session_destroy();
        header("Location: ../index.php?timeout=1");
        exit();
    }
    $_SESSION['last_activity'] = time();
}

function checkAdmin()
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: dashboard.php");
        exit();
    }
}
