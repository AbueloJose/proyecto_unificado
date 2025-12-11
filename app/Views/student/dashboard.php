<?php include '../app/Views/layouts/header.php'; ?>

<style>
    /* (Mismos estilos del chat flotante de antes) */
    .chat-widget-btn { position: fixed; bottom: 25px; right: 25px; width: 60px; height: 60px; background-color: #0d6efd; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.4); cursor: pointer; z-index: 1000; transition: transform 0.3s; }
    .chat-widget-btn:hover { transform: scale(1.1); background-color: #0b5ed7; }
    .chat-window { position: fixed; bottom: 100px; right: 25px; width: 350px; height: 450px; background: white; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.15); z-index: 1000; display: none; flex-direction: column; overflow: hidden; border: 1px solid #f0f0f0; opacity: 0; transform: translateY(20px); transition: all 0.3s ease; }
    .chat-window.active { display: flex; opacity: 1; transform: translateY(0); }
    .chat-header { background: #0d6efd; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
    .chat-body { flex: 1; background-color: #f8f9fa; padding: 15px; overflow-y: auto; }
    .chat-footer { padding: 10px; background: white; border-top: 1px solid #eee; }
    @media (max-width: 576px) { .chat-window { width: 90%; right: 5%; bottom: 90px; } }
</style>

<div class="container-fluid">
    <div class="row">
        
        <?php include '../app/Views/layouts/student_sidebar.php'; ?>

        <div class="col-md-10 p-4" style="background-color: #f8f9fa;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-0">Panel de Control</h2>
                    <p class="text-muted mb-0">
                        Estado: 
                        <?php if(isset($tienePractica) && $tienePractica): ?>
                            <span class="badge bg-success rounded-pill px-3">En Prácticas - <?= $empresaNombre ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary rounded-pill px-3">Sin Asignación</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-calendar-check text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Informes Enviados</h6>
                                <h3 class="fw-bold mb-0">
                                    <?= $conteoInformes ?> <small class="text-muted fs-6">/ 16</small>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-graph-up-arrow text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Predicción Éxito (IA)</h6>
                                <h3 class="fw-bold mb-0 <?= $clasePrediccion ?>">
                                    <?= $prediccionIA ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-star-fill text-warning fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Promedio Eval.</h6>
                                <h3 class="fw-bold mb-0">
                                    <?= $promedioGeneral > 0 ? $promedioGeneral : '-' ?> 
                                    <small class="text-muted fs-6">/ 20</small>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="fw-bold mb-0"><i class="bi bi-radar"></i> Mapa de Competencias</h5>
                        </div>
                        <div class="card-body" style="min-height: 400px;">
                            <?php if(isset($tienePractica) && $tienePractica): ?>
                                <?php if($promedioGeneral > 0): ?>
                                    <div id="chart-competencias"></div>
                                <?php else: ?>
                                    <div class="alert alert-light text-center py-5">
                                        <i class="bi bi-bar-chart fs-1 text-muted opacity-50 mb-3"></i>
                                        <p class="text-muted">Aún no tienes evaluaciones registradas por tu docente.</p>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-light text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                    <p class="text-muted">Cuando inicies tus prácticas, verás aquí tu progreso.</p>
                                    <a href="<?= BASE_URL ?>student/opportunities" class="btn btn-sm btn-primary mt-2">Buscar Prácticas</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="chat-widget-btn" onclick="toggleChat()"><i class="bi bi-chat-dots-fill" id="chat-icon"></i></div>
<div class="chat-window" id="chat-window">
    <div class="chat-header">
        <div class="d-flex align-items-center gap-2"><i class="bi bi-robot"></i><span class="fw-bold">Asistente Virtual</span></div>
        <button type="button" class="btn-close btn-close-white" onclick="toggleChat()"></button>
    </div>
    <div class="chat-body" id="chat-history">
        <div class="d-flex flex-row justify-content-start mb-3">
            <div class="bg-white p-2 rounded-circle me-2 border shadow-sm" style="width: 35px; height: 35px; display:flex; align-items:center; justify-content:center;">🤖</div>
            <div class="p-2 bg-white text-dark rounded-3 shadow-sm small" style="max-width: 85%;">Hola <strong><?= $_SESSION['user_name'] ?></strong>. ¿En qué te ayudo?</div>
        </div>
    </div>
    <div class="chat-footer">
        <div class="input-group input-group-sm">
            <input type="text" id="chat-input" class="form-control" placeholder="Escribe aquí..." autocomplete="off">
            <button class="btn btn-primary" onclick="sendMessage()"><i class="bi bi-send-fill"></i></button>
        </div>
    </div>
</div>

<script>
    // --- GRÁFICO (Solo se renderiza si hay datos) ---
    var userScores = <?= isset($jsonCompetencias) ? $jsonCompetencias : '[0,0,0,0,0]' ?>;
    
    // Solo dibujamos si hay algún valor mayor a 0
    if (userScores.some(score => score > 0)) {
        var options = {
            series: [{ name: 'Tu Nivel', data: userScores }, { name: 'Meta', data: [16, 18, 16, 18, 20] }],
            chart: { height: 350, type: 'radar', toolbar: { show: false }, fontFamily: 'Segoe UI' },
            labels: ['Técnico', 'Comunicación', 'Equipo', 'Resolución', 'Puntualidad'],
            stroke: { width: 2, colors: ['#0d6efd', '#adb5bd'] },
            fill: { opacity: 0.2 },
            markers: { size: 4 },
            yaxis: { max: 20, min: 0, show: false },
            colors: ['#0d6efd', '#6c757d']
        };
        new ApexCharts(document.querySelector("#chart-competencias"), options).render();
    }

    // --- CHATBOT ---
    function toggleChat() {
        const win = document.getElementById('chat-window');
        const icon = document.getElementById('chat-icon');
        if (win.style.display === 'none' || win.style.display === '') {
            win.style.display = 'flex';
            setTimeout(() => win.classList.add('active'), 10);
            icon.classList.replace('bi-chat-dots-fill', 'bi-x-lg');
            loadChatHistory();
        } else {
            win.classList.remove('active');
            setTimeout(() => win.style.display = 'none', 300);
            icon.classList.replace('bi-x-lg', 'bi-chat-dots-fill');
        }
    }
    // ... (El resto del script del chat es el mismo de siempre) ...
    document.getElementById("chat-input").addEventListener("keypress", function(e) { if (e.key === "Enter") sendMessage(); });
    function loadChatHistory() { fetch('<?= BASE_URL ?>chat/history').then(res=>res.json()).then(d=>{ let b=document.getElementById('chat-history'); b.innerHTML=''; d.forEach(c=>{appendMessage('user',c.mensaje_usuario);appendMessage('bot',c.mensaje_bot);}); scrollToBottom(); }).catch(e=>console.log("Chat off")); }
    function sendMessage() { let i=document.getElementById('chat-input'); let m=i.value.trim(); if(m==="")return; appendMessage('user',m); i.value=""; scrollToBottom(); let l=showLoader(); fetch('<?= BASE_URL ?>chat/send',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({mensaje:m})}).then(r=>r.json()).then(d=>{removeLoader(l);appendMessage('bot',d.respuesta);scrollToBottom();}); }
    function appendMessage(s,t) { let b=document.getElementById('chat-history'); let h=s==='user'?`<div class="d-flex justify-content-end mb-2"><div class="p-2 bg-primary text-white rounded-3 small" style="max-width:85%;">${t}</div></div>`:`<div class="d-flex justify-content-start mb-2"><div class="bg-white p-2 rounded-circle me-1 border" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;">🤖</div><div class="p-2 bg-white text-dark rounded-3 border small" style="max-width:85%;">${t}</div></div>`; b.insertAdjacentHTML('beforeend',h); }
    function showLoader() { let id="l-"+Date.now(); document.getElementById('chat-history').insertAdjacentHTML('beforeend',`<div id="${id}" class="text-muted small ms-4 mb-2">...</div>`); scrollToBottom(); return id; }
    function removeLoader(id) { let el=document.getElementById(id); if(el)el.remove(); }
    function scrollToBottom() { let b=document.getElementById('chat-history'); b.scrollTop=b.scrollHeight; }
</script>

<?php include '../app/Views/layouts/footer.php'; ?>