<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Pendaftaran</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }
        .logo-left {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 48px;
            height: 48px;
            background: none;
        }
        .logo-right {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 48px;
            height: 48px;
            background: none;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-left: 70px; /* beri jarak agar tidak tertutup logo kiri */
            margin-right: 70px; /* beri jarak agar tidak tertutup logo kanan */
        }
      
     .box {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 20px;
/*************  ✨ Windsurf Command ⭐  *************/
        }
        .box button:hover {
          background-color: #2c3e50;
         width: 8%;
      padding: 5px;
    background-color: #3498db;
     }
        
        .header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border-radius: 4px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="peepus.jpg" alt="Logo Kiri" class="logo-left">
        <img src="smk.jpg" alt="Logo Kanan" class="logo-right">
        <center><h1>PERPUSTAKAN DIGITAL</h1></center>
        <div class="header">
            
            <span>Website peminjaman buku </span>
            
        </div>
        <ul class="box">
            
             <a href="login.php" class="file-name">Masuk</a>
                </span>

            </li>
        </ul>
        <p style="margin-top: 20px; font-size: 0.9em; color: #7f8c8d;">
            Sistem berbasis web untuk pendaftaran pengguna baru.
        </p>
    </div>
</body>
</html>