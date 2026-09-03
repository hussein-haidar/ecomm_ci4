<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <!-- Icon -->
    <link href="<?= base_url() ?>/icon/gudang.ico" rel="shortcut icon">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #3c8ce7, #00eaff);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 600px;
        }

        h1 {
            font-size: 120px;
            margin: 0;
            animation: fadeIn 2s ease-in-out;
        }

        h2 {
            font-size: 24px;
            margin: 10px 0 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff6b6b;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #ff4757;
        }

        .error-image {
            width: 150px;
            margin-bottom: 20px;
            animation: float 3s infinite ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="<?= base_url('icon/gudang.ico'); ?>" alt="404 Not Found" class="error-image">
        <h1>404</h1>
        <h2>Oops! Halaman Tidak Ditemukan</h2>
        <p>
            Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan. <br>
            Silakan kembali ke beranda atau coba lagi nanti.
        </p>
        <a href="<?= base_url(); ?>">Kembali ke Beranda</a>
    </div>
</body>

</html>