<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Direct Send</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #25D366, #128C7E);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            max-width: 480px;
            width: 100%;
        }
        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }
        .wa-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #25D366;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .wa-icon svg {
            width: 26px;
            height: 26px;
            fill: white;
        }
        .header-text h1 {
            font-size: 18px;
            color: #1a1a1a;
            font-weight: 700;
        }
        .header-text p {
            font-size: 13px;
            color: #888;
            margin-top: 2px;
        }
        .input-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }
        .phone-display {
            width: 100%;
            padding: 12px 16px;
            background: #f5f5f5;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .phone-display span {
            color: #128C7E;
            font-weight: 600;
        }
        textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
            transition: border-color 0.3s;
            color: #333;
        }
        textarea:focus {
            outline: none;
            border-color: #25D366;
        }
        textarea::placeholder {
            color: #bbb;
        }
        .btn {
            width: 100%;
            padding: 15px;
            background: #25D366;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.2s, transform 0.15s;
            margin-top: 4px;
        }
        .btn:hover {
            background: #128C7E;
            transform: translateY(-2px);
        }
        .btn:active {
            transform: translateY(0);
        }
        .btn svg {
            width: 20px;
            height: 20px;
            fill: white;
        }
        .hint {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="wa-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </div>
            <div class="header-text">
                <h1>Kirim Pesan WhatsApp</h1>
                <p>Pesan langsung tanpa menyimpan kontak</p>
            </div>
        </div>

        <div class="input-group">
            <label>📱 Tujuan</label>
            <div class="phone-display">
                <span>+62 895-1784-7356</span>
            </div>
        </div>

        <div class="input-group">
            <label>💬 Pesan</label>
            <textarea id="message" placeholder="Ketik pesan kamu di sini..."></textarea>
        </div>

        <button class="btn" onclick="sendWA()">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Buka WhatsApp
        </button>

        <p class="hint">Akan membuka WhatsApp atau WhatsApp Web secara otomatis &bull; Ctrl+Enter untuk kirim cepat</p>
    </div>

    <script>
        function sendWA() {
            const msg = document.getElementById('message').value.trim();
            const phone = '6289517847356';
            let url = 'https://wa.me/' + phone;
            if (msg) url += '?text=' + encodeURIComponent(msg);
            window.open(url, '_blank');
        }

        document.getElementById('message').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.ctrlKey) {
                e.preventDefault();
                sendWA();
            }
        });
    </script>
</body>
</html>