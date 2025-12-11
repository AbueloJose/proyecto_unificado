<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Sistema Unificado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #e9ecef; }
        .register-card { max-width: 900px; margin: 40px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
        .video-box { position: relative; width: 100%; height: 320px; background: #000; border-radius: 8px; overflow: hidden; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; }
        video, canvas { max-width: 100%; max-height: 100%; object-fit: contain; transform: scaleX(-1); }
        .form-section { padding: 2.5rem; }
        .camera-section { padding: 2.5rem; background-color: #f8f9fa; border-left: 1px solid #eee; }
    </style>
</head>
<body>

<div class="container">
    <div class="register-card row g-0">
        
        <div class="col-md-6 form-section">
            <h3 class="fw-bold mb-4">Crear Cuenta</h3>
            
            <form id="formRegistro" action="<?= BASE_URL ?>auth/store" method="POST">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold">Nombres</label>
                        <input type="text" name="nombres" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Email Institucional</label>
                    <input type="email" name="email" class="form-control" required placeholder="ejemplo@uni.edu.pe">
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-bold">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <input type="hidden" name="rol" value="estudiante">
                
                <input type="hidden" name="biometria_base64" id="biometria_base64">
                <input type="hidden" name="face_descriptor" id="face_descriptor">

                <div class="alert alert-warning small py-2" id="alerta-foto">
                    <i class="bi bi-camera-video-fill me-2"></i> Debes capturar tu rostro.
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" id="btn-submit" disabled>
                    Registrarse
                </button>
                
                <div class="text-center mt-3">
                    <a href="<?= BASE_URL ?>auth/login" class="small text-decoration-none">Ya tengo cuenta</a>
                </div>
            </form>
        </div>

        <div class="col-md-6 camera-section text-center">
            <h5 class="mb-2 fw-bold"><i class="bi bi-person-bounding-box"></i> Registro Facial</h5>
            <p class="text-muted small mb-3">Necesario para entrar sin contraseña.</p>
            
            <div class="video-box shadow-sm">
                <video id="video" autoplay playsinline muted></video>
                <canvas id="canvas-preview" style="display:none; position:absolute; top:0; left:0;"></canvas>
            </div>
            
            <div id="loading-ia" class="text-primary small fw-bold mb-2">Cargando IA...</div>

            <div class="d-grid gap-2">
                <button type="button" class="btn btn-dark" id="btn-capturar" onclick="capturarFoto()" disabled>
                    <i class="bi bi-camera-fill me-1"></i> Capturar Rostro
                </button>
                <button type="button" class="btn btn-outline-danger" id="btn-retake" onclick="repetirFoto()" style="display:none;">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Repetir
                </button>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas-preview');
    const inputBase64 = document.getElementById('biometria_base64');
    const inputDescriptor = document.getElementById('face_descriptor');
    const btnSubmit = document.getElementById('btn-submit');
    const btnCapturar = document.getElementById('btn-capturar');
    const btnRetake = document.getElementById('btn-retake');
    const alerta = document.getElementById('alerta-foto');
    const loadingText = document.getElementById('loading-ia');
    let modelsLoaded = false;

    async function loadModels() {
        try {
            // Usamos CDN directo para asegurar que carguen los modelos
            const modelPath = 'https://justadudewhohacks.github.io/face-api.js/models';
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(modelPath),
                faceapi.nets.faceLandmark68Net.loadFromUri(modelPath),
                faceapi.nets.faceRecognitionNet.loadFromUri(modelPath),
                faceapi.nets.ssdMobilenetv1.loadFromUri(modelPath)
            ]);
            modelsLoaded = true;
            loadingText.style.display = "none";
            btnCapturar.disabled = false;
            startCamera();
        } catch (e) {
            loadingText.innerText = "Error conexión IA";
            loadingText.className = "text-danger small fw-bold mb-2";
        }
    }

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: {} })
            .then(stream => { video.srcObject = stream; })
            .catch(err => console.error(err));
    }

    async function capturarFoto() {
        if(!modelsLoaded) return;
        btnCapturar.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';
        
        const detection = await faceapi.detectSingleFace(video, new faceapi.SsdMobilenetv1Options()).withFaceLandmarks().withFaceDescriptor();

        if (detection) {
            const descriptorArray = Array.from(detection.descriptor);
            inputDescriptor.value = JSON.stringify(descriptorArray);

            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            inputBase64.value = canvas.toDataURL('image/png');

            canvas.style.display = 'block';
            video.style.display = 'none';
            btnCapturar.style.display = 'none';
            btnRetake.style.display = 'block';
            btnSubmit.disabled = false;
            alerta.className = "alert alert-success small py-2";
            alerta.innerHTML = "<i class='bi bi-check-circle-fill'></i> Rostro validado.";
        } else {
            alert("No se detectó un rostro claro. Intenta de nuevo.");
            btnCapturar.innerHTML = '<i class="bi bi-camera-fill me-1"></i> Capturar Rostro';
        }
    }

    function repetirFoto() {
        inputBase64.value = "";
        inputDescriptor.value = "";
        canvas.style.display = 'none';
        video.style.display = 'block';
        btnCapturar.style.display = 'block';
        btnRetake.style.display = 'none';
        btnSubmit.disabled = true;
        btnCapturar.innerHTML = '<i class="bi bi-camera-fill me-1"></i> Capturar Rostro';
        alerta.className = "alert alert-warning small py-2";
        alerta.innerHTML = "Debes capturar tu rostro.";
    }

    window.addEventListener('load', loadModels);
</script>

</body>
</html>