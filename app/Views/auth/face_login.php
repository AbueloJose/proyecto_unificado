<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Facial Automático</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .login-card { width: 100%; max-width: 500px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; }
        .video-container { position: relative; width: 100%; height: 350px; background: #000; display: flex; align-items: center; justify-content: center; }
        video { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
        .status-overlay { position: absolute; bottom: 0; left: 0; width: 100%; background: rgba(0,0,0,0.7); color: white; text-align: center; padding: 15px; font-weight: 600; transition: background 0.3s; }
        .scan-line { position: absolute; width: 100%; height: 2px; background: #0d6efd; box-shadow: 0 0 15px #0d6efd; top: 0; left: 0; animation: scan 2s infinite linear; display: none; z-index: 10; }
        @keyframes scan { 0% {top: 0;} 50% {top: 100%;} 100% {top: 0;} }
    </style>
</head>
<body>

<div class="login-card">
    <div class="p-3 text-center border-bottom">
        <h5 class="fw-bold mb-0"><i class="bi bi-person-bounding-box text-primary me-2"></i>Reconocimiento Facial</h5>
    </div>

    <div class="video-container">
        <video id="video" autoplay muted playsinline></video>
        <div class="scan-line" id="scan-line"></div>
        <div class="status-overlay" id="status-text">Cargando sistema...</div>
    </div>

    <div class="p-4 text-center">
        <div id="user-greeting" class="h5 fw-bold mb-3 text-dark" style="min-height: 25px;"></div>
        
        <button class="btn btn-primary w-100 mb-3 fw-bold" id="btn-start" onclick="processFace()" disabled>
            <i class="bi bi-camera-fill me-2"></i> Iniciar Escaneo
        </button>
        
        <a href="<?= BASE_URL ?>auth/login" class="text-decoration-none text-secondary small">
            Ingresar con contraseña
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

<script>
    const video = document.getElementById('video');
    const statusText = document.getElementById('status-text');
    const btnStart = document.getElementById('btn-start');
    const greeting = document.getElementById('user-greeting');
    const scanLine = document.getElementById('scan-line');
    let modelsLoaded = false;

    // 1. Cargar Modelos
    async function loadModels() {
        try {
            const modelPath = 'https://justadudewhohacks.github.io/face-api.js/models';
            statusText.innerText = "Descargando IA...";
            
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(modelPath),
                faceapi.nets.faceLandmark68Net.loadFromUri(modelPath),
                faceapi.nets.faceRecognitionNet.loadFromUri(modelPath),
                faceapi.nets.ssdMobilenetv1.loadFromUri(modelPath)
            ]);

            modelsLoaded = true;
            statusText.innerText = "Cámara lista.";
            startCamera();
        } catch (e) {
            statusText.innerText = "Error cargando modelos.";
        }
    }

    // 2. Iniciar Cámara
    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: {} })
            .then(stream => {
                video.srcObject = stream;
                statusText.innerText = "Mira fijamente a la cámara";
                btnStart.disabled = false;
            });
    }

    // 3. Procesar
    async function processFace() {
        if(!modelsLoaded) return;

        btnStart.disabled = true;
        btnStart.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Buscando en la base de datos...';
        scanLine.style.display = 'block';
        greeting.innerText = "";

        try {
            // Detectar cara
            const detection = await faceapi.detectSingleFace(video, new faceapi.SsdMobilenetv1Options()).withFaceLandmarks().withFaceDescriptor();

            if (detection) {
                statusText.innerText = "Analizando rostro...";
                const descriptor = Array.from(detection.descriptor);

                // ENVIAR AL SERVIDOR PARA BUSCAR ENTRE TODOS LOS USUARIOS
                const res = await fetch('<?= BASE_URL ?>auth/verify_face_auto', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ descriptor: descriptor })
                });

                const data = await res.json();
                scanLine.style.display = 'none';

                if (data.success) {
                    // ¡ENCONTRADO!
                    statusText.innerText = "¡Identificado!";
                    statusText.style.background = "rgba(25, 135, 84, 0.9)"; // Verde
                    
                    greeting.innerHTML = `¡Bienvenido, <span class="text-primary">${data.user_name}</span>!`;
                    btnStart.className = "btn btn-success w-100 mb-3";
                    btnStart.innerText = "Redirigiendo...";
                    
                    setTimeout(() => { window.location.href = data.redirect; }, 1500);
                } else {
                    // NO ENCONTRADO
                    statusText.innerText = "Usuario no reconocido";
                    statusText.style.background = "rgba(220, 53, 69, 0.8)"; // Rojo
                    btnStart.disabled = false;
                    btnStart.innerHTML = "Reintentar";
                    btnStart.className = "btn btn-warning w-100 mb-3 text-dark fw-bold";
                }
            } else {
                scanLine.style.display = 'none';
                statusText.innerText = "No veo ningún rostro";
                btnStart.disabled = false;
                btnStart.innerText = "Reintentar";
            }
        } catch (e) {
            console.error(e);
            scanLine.style.display = 'none';
            btnStart.disabled = false;
            btnStart.innerText = "Error Técnico";
        }
    }

    window.addEventListener('load', loadModels);
</script>
</body>
</html>