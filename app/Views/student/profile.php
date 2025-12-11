<?php include '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        
        <?php include '../app/Views/layouts/student_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f8f9fa;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Mi Perfil</h2>
            </div>

            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i> Guardado correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Error al guardar. Verifica el formato.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body text-center pt-5 pb-4">
                            <div class="position-relative d-inline-block mb-3">
                                <img src="<?= !empty($userData['foto_perfil']) ? BASE_URL . $userData['foto_perfil'] : 'https://ui-avatars.com/api/?name=' . ($userData['nombres'] ?? 'User') . '&background=0d6efd&color=fff' ?>" 
                                     class="rounded-circle shadow-sm" width="120" height="120" style="object-fit: cover;">
                                
                                <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" data-bs-toggle="modal" data-bs-target="#modalFoto">
                                    <i class="bi bi-camera-fill"></i>
                                </button>
                            </div>
                            <h5 class="fw-bold"><?= ($userData['nombres'] ?? '') . ' ' . ($userData['apellidos'] ?? '') ?></h5>
                            <p class="text-muted small"><?= $userData['email'] ?? '' ?></p>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Estudiante</span>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Currículum Vitae</h6>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($userData['cv_path'])): ?>
                                <div class="alert alert-light border d-flex justify-content-between align-items-center p-2">
                                    <span class="small text-truncate" style="max-width: 140px;">Mi_Curriculum.pdf</span>
                                    <a href="<?= BASE_URL . $userData['cv_path'] ?>" target="_blank" class="btn btn-sm btn-outline-dark"><i class="bi bi-eye"></i></a>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-3 text-muted small">No has subido tu CV</div>
                            <?php endif; ?>
                            
                            <form action="<?= BASE_URL ?>student/update_cv" method="POST" enctype="multipart/form-data" class="mt-2">
                                <div class="input-group input-group-sm">
                                    <input type="file" name="cv" class="form-control" required accept=".pdf">
                                    <button class="btn btn-dark" type="submit">Subir</button>
                                </div>
                                <small class="text-muted" style="font-size: 10px;">Solo PDF (Max 5MB)</small>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="fw-bold mb-0">Información Personal</h6>
                        </div>
                        <div class="card-body">
                            <form action="<?= BASE_URL ?>student/update_data" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted fw-bold">Teléfono</label>
                                        <input type="text" name="telefono" class="form-control" value="<?= $userData['telefono'] ?? '' ?>" placeholder="+51 999...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted fw-bold">Email de Respaldo</label>
                                        <input type="email" name="email_respaldo" class="form-control" value="<?= $userData['email_respaldo'] ?? '' ?>" placeholder="personal@gmail.com">
                                    </div>
                                    <div class="col-12 text-end mt-3">
                                        <button type="submit" class="btn btn-primary px-4 fw-bold">Guardar Cambios</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0"><i class="bi bi-person-bounding-box text-primary me-2"></i> Seguridad Biométrica</h6>
                            <span class="badge bg-success">Activo</span>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <p class="small text-muted mb-0">
                                        Registra tu rostro para iniciar sesión sin contraseña desde cualquier dispositivo con cámara.
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button class="btn btn-outline-dark btn-sm fw-bold" onclick="startFaceRegistration()">
                                        <i class="bi bi-camera me-1"></i> Escanear Rostro
                                    </button>
                                </div>
                            </div>
                            
                            <div id="camera-container" class="mt-3 p-3 bg-light rounded text-center border" style="display: none;">
                                <video id="video" width="320" height="240" autoplay muted class="rounded shadow-sm mb-2" style="transform: scaleX(-1); max-width: 100%;"></video>
                                <div class="d-block">
                                    <button id="btn-scan" class="btn btn-primary btn-sm" onclick="captureAndRegister()">
                                        <i class="bi bi-record-circle me-1"></i> Registrar Ahora
                                    </button>
                                    <button class="btn btn-link text-danger btn-sm text-decoration-none" onclick="stopCamera()">Cancelar</button>
                                </div>
                                <div id="scan-status" class="small mt-2 fw-bold text-muted">Iniciando cámara...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFoto" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Nueva Foto de Perfil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?= BASE_URL ?>student/update_photo" method="POST" enctype="multipart/form-data">
                    <input type="file" name="foto" class="form-control mb-3" accept="image/*" required>
                    <button type="submit" class="btn btn-primary w-100">Subir Imagen</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>
<script>
    let video = document.getElementById('video');
    let streamActive = false;

    async function startFaceRegistration() {
        document.getElementById('camera-container').style.display = 'block';
        document.getElementById('scan-status').innerText = "Conectando con IA...";
        
        try {
            const modelUrl = 'https://justadudewhohacks.github.io/face-api.js/models';
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(modelUrl),
                faceapi.nets.faceLandmark68Net.loadFromUri(modelUrl),
                faceapi.nets.faceRecognitionNet.loadFromUri(modelUrl),
                faceapi.nets.ssdMobilenetv1.loadFromUri(modelUrl)
            ]);

            document.getElementById('scan-status').innerText = "Cámara activa...";
            
            navigator.mediaDevices.getUserMedia({ video: {} })
                .then(stream => { video.srcObject = stream; streamActive = stream; })
                .catch(err => alert("Error de cámara: " + err));
        } catch (e) {
            document.getElementById('scan-status').innerText = "Error cargando modelos IA";
        }
    }

    async function captureAndRegister() {
        document.getElementById('scan-status').innerText = "Procesando...";
        document.getElementById('btn-scan').disabled = true;

        const detections = await faceapi.detectSingleFace(video, new faceapi.SsdMobilenetv1Options()).withFaceLandmarks().withFaceDescriptor();

        if (detections) {
            const descriptor = Array.from(detections.descriptor);
            fetch('<?= BASE_URL ?>student/save_face', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ descriptor: descriptor })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert("¡Rostro actualizado con éxito!");
                    stopCamera();
                } else {
                    alert("Error: " + data.message);
                    document.getElementById('btn-scan').disabled = false;
                }
            });
        } else {
            alert("No se detectó rostro. Inténtalo de nuevo.");
            document.getElementById('btn-scan').disabled = false;
        }
    }

    function stopCamera() {
        if(streamActive) streamActive.getTracks().forEach(t => t.stop());
        document.getElementById('camera-container').style.display = 'none';
    }
</script>

<?php include '../app/Views/layouts/footer.php'; ?>