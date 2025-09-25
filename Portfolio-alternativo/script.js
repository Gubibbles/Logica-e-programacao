function updateClock() {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    
    const clockElement = document.querySelector('.tray-clock');
    if(clockElement) {
        clockElement.textContent = `${hours}:${minutes}`;
    }
}

setInterval(updateClock, 60000);

// SISTEMA DE SOM
let soundsEnabled = false;
let soundsLoaded = false;
const sounds = {};

// CARREGAR SONS
function loadSounds() {
    if (soundsLoaded) return;
    
    const soundFiles = {
        click: 'sons/click.wav',
        windowOpen: 'sons/window-open.wav',
        recycle: 'sons/recycle.wav',
        startup: 'sons/startup.wav'
    };

    Object.entries(soundFiles).forEach(([name, src]) => {
        sounds[name] = new Audio(src);
        sounds[name].preload = 'auto';
        sounds[name].volume = 0.4;
    });
    
    soundsLoaded = true;
    console.log('Sons carregados!');
}

// FUNÇÃO PARA TOCAR SONS
function playSound(soundName, volume = 0.4) {
    if (!soundsEnabled || !sounds[soundName]) return;
    
    try {
        const sound = sounds[soundName].cloneNode();
        sound.volume = volume;
        sound.play().catch(error => {
            console.log('Som não pode ser reproduzido:', error);
        });
    } catch (error) {
        console.log('Erro ao reproduzir som:', error);
    }
}

// ATIVAR SONS APÓS PRIMEIRA INTERAÇÃO
function enableSounds() {
    if (soundsEnabled) return;
    
    soundsEnabled = true;
    console.log('Sons ativados!');
    
    playSound('click', 0.5);
    
    document.removeEventListener('click', enableSounds);
    document.removeEventListener('mousedown', enableSounds);
    document.removeEventListener('keydown', enableSounds);
}

// CONFIGURAR SISTEMA DE SOM
function setupSoundSystem() {
    loadSounds();
    
    document.addEventListener('click', enableSounds, { once: true });
    document.addEventListener('mousedown', enableSounds, { once: true });
    document.addEventListener('keydown', enableSounds, { once: true });
}

// CONFIGURAR SONS PARA ELEMENTOS INTERATIVOS
function setupSounds() {
    // Botões da taskbar
    const taskbarButtons = document.querySelectorAll('.windows-button, .start-button');
    taskbarButtons.forEach(button => {
        button.addEventListener('mouseenter', () => playSound('hover', 0.3));
        button.addEventListener('click', () => playSound('click', 0.4));
    });
    
    // Ícones da área de trabalho
    const desktopIcons = document.querySelectorAll('.icone');
    desktopIcons.forEach(icon => {
        icon.addEventListener('mouseenter', () => playSound('hover', 0.2));
        icon.addEventListener('click', () => playSound('click', 0.3));
    });
    
    // Botões de controle das janelas
    const windowControls = document.querySelectorAll('.title-bar-controls button');
    windowControls.forEach(button => {
        button.addEventListener('mouseenter', () => playSound('hover', 0.3));
        button.addEventListener('click', () => playSound('click', 0.4));
    });
    
    // Botões personalizados
    const xpButtons = document.querySelectorAll('.xp-button, .control-btn');
    xpButtons.forEach(button => {
        button.addEventListener('mouseenter', () => playSound('hover', 0.3));
        button.addEventListener('click', () => playSound('click', 0.4));
    });
    
    console.log('Sistema de som configurado!');
}

// ABRIR A JANELA
function abrirJanela(idJanela) {
    if (startMenuOpen){
        fecharStartMenu();
    }
    const janela = document.getElementById(idJanela);
    if (!janela) {
        console.error('Janela não encontrada:', idJanela);
        return;
    }

    // Responsividade
    if (window.innerWidth <= 768) {
        janela.style.width = '95%';
        janela.style.height = '80vh';
        janela.style.left = '2.5%';
        janela.style.top = '20px';
    } else {
        if (janela.style.left && janela.style.top) {
            janela.style.display = 'flex';
        } else {
            const windowWidth = 700;
            const windowHeight = 550;
            const left = (window.innerWidth - windowWidth) / 2;
            const top = 50;
            
            janela.style.left = left + 'px';
            janela.style.top = top + 'px';
        }
    }

    janela.style.display = 'flex';
    playSound('windowOpen', 0.5); //
    
    if (janela.style.left && janela.style.top) {
        janela.style.display = 'flex';
    } else {
        janela.style.display = 'flex';
        const windowWidth = 700;
        const windowHeight = 550;
        const left = (window.innerWidth - windowWidth) / 2;
        const top = 50;
        
        janela.style.left = left + 'px';
        janela.style.top = top + 'px';
    }

    playSound('windowOpen', 0.5);

    if (idJanela === 'sobre-mim-window') {
        document.getElementById('sobre-mim-button').classList.add('active');
    } else if (idJanela === 'trabalhos-window') {
        document.getElementById('trabalhos-button').classList.add('active');
    } else if (idJanela === 'links-window') {
        document.getElementById('links-button').classList.add('active');
    } else if (idJanela === 'music-window') {
        document.getElementById('music-button').classList.add('active');
        setTimeout(() => {
            initializeMusicPlayer();
            const playlistItems = document.querySelectorAll('#playlist li');
            if (playlistItems.length > 0) {
                currentSongIndex = 0;
                loadAndPlaySong().catch(error => {
                    console.log('Aguardando interação do usuário para reproduzir');
                });
            }
        }, 100);
    }
}

// FECHAR A JANELA
function fecharJanela(idJanela) {
    const janela = document.getElementById(idJanela);
    if (!janela) {
        console.error('Janela não encontrada:', idJanela);
        return;
    }
    janela.style.display = 'none';
    
    if (idJanela === 'sobre-mim-window') {
        document.getElementById('sobre-mim-button').classList.remove('active');
    } else if (idJanela === 'trabalhos-window') {
        document.getElementById('trabalhos-button').classList.remove('active');
    } else if (idJanela === 'links-window') {
        document.getElementById('links-button').classList.remove('active');
    } else if (idJanela === 'music-window') {
        document.getElementById('music-button').classList.remove('active');
        if (audioPlayer) {
            stopSong();
        }
    }
}

// FUNÇÃO PARA ARRASTAR JANELA
function makeDraggable(windowElement, titleBarElement) {
    if (!windowElement || !titleBarElement) {
        console.error('Elemento não encontrado para arrasto');
        return;
    }
    
    let isDragging = false;
    let offset = [0, 0];

    titleBarElement.addEventListener("mousedown", function(e) {
        isDragging = true;
        
        offset = [
            windowElement.offsetLeft - e.clientX,
            windowElement.offsetTop - e.clientY
        ];
        
        windowElement.classList.add("dragging");
    });

    document.addEventListener("mouseup", function() {
        if (!isDragging) return;
        
        isDragging = false;
        windowElement.classList.remove("dragging");
    });

    document.addEventListener("mousemove", function(e) {
        e.preventDefault();
        if (!isDragging) return;
        
        const newLeft = e.clientX + offset[0];
        const newTop = e.clientY + offset[1];
        
        const maxLeft = window.innerWidth - windowElement.offsetWidth;
        const maxTop = window.innerHeight - windowElement.offsetHeight;
        
        windowElement.style.left = Math.max(0, Math.min(newLeft, maxLeft)) + 'px';
        windowElement.style.top = Math.max(0, Math.min(newTop, maxTop)) + 'px';
    });
}

// FUNÇÃO PARA REDIMENSIONAR JANELA
function makeResizable(windowElement, resizeHandle) {
    if (!windowElement || !resizeHandle) {
        console.error('Elementos de redimensionamento não encontrados');
        return;
    }
    
    resizeHandle.addEventListener('mousedown', function(e) {
        e.preventDefault();

        windowElement.classList.add('resizing');
        document.body.classList.add('window-resizing');
        
        const startX = e.clientX;
        const startY = e.clientY;
        
        const startWidth = windowElement.offsetWidth;
        const startHeight = windowElement.offsetHeight;
        
        const minWidth = 400;
        const minHeight = 300;
        
        function onMouseMove(e) {
            const newWidth = Math.max(minWidth, startWidth + (e.clientX - startX));
            const newHeight = Math.max(minHeight, startHeight + (e.clientY - startY));
            
            windowElement.style.width = newWidth + 'px';
            windowElement.style.height = newHeight + 'px';
        }
        
        function onMouseUp() {
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);

            windowElement.classList.remove('resizing');
            document.body.classList.remove('window-resizing');
        }
        
        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);
    });
}

// FUNÇÃO PARA ABRIR IMAGEM EM TAMANHO MAIOR
function abrirModal(imgSrc) {
    const modal = document.getElementById('image-modal');
    const modalImg = document.getElementById('modal-image');

    if (!modal || !modalImg) {
        console.error('Modal não encontrado');
        return;
    }

    modal.style.display = 'flex';
    modalImg.src = imgSrc;
}

// FUNÇÃO PARA FECHAR MODAL
function fecharModal() {
    const modal = document.getElementById('image-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => playSound('startup', 0.6), 500);
    setupSoundSystem();
    updateClock();
    setupMinecraftIcon();
    setupLixeiraIcon();
    setupSounds();

    const sobreMimButton = document.getElementById('sobre-mim-button');
    const sobreMimIcon = document.getElementById('meus-documentos');
    const startButton = document.querySelector('.start-button');

    const trabalhosButton = document.getElementById('trabalhos-button');
    const trabalhosIcon = document.getElementById('trabalhos-icon');

    const linksButton = document.getElementById('links-button');
    const linksIcon = document.getElementById('links-icon');
    
    // Janelas
    const sobreMimWindow = document.getElementById('sobre-mim-window');
    const sobreMimTitlebar = document.getElementById('sobre-mim-titlebar');

    const trabalhosWindow = document.getElementById('trabalhos-window');
    const trabalhosTitlebar = document.getElementById('trabalhos-titlebar');
    
    const linksWindow = document.getElementById('links-window');
    const linksTitlebar = document.getElementById('links-titlebar');

    const musicButton = document.getElementById('music-button');
    const musicIcon = document.getElementById('music-icon');

    const musicWindow = document.getElementById('music-window');
    const musicTitlebar = document.getElementById('music-titlebar');

    // ONFIGURAR REDIMENSIONAMENTO PARA TODAS AS JANELAS

    const sobreMimResize = document.querySelector('#sobre-mim-window .window-resize-handle');
    const trabalhosResize = document.querySelector('#trabalhos-window .window-resize-handle');
    const linksResize = document.querySelector('#links-window .window-resize-handle');
    const musicResizeHandle = musicWindow ? musicWindow.querySelector('.window-resize-handle') : null;

    if (musicWindow && musicResizeHandle) {
        makeResizable(musicWindow, musicResizeHandle);
        console.log('Redimensionamento configurado para Music Player');
    }

    if (sobreMimWindow && sobreMimResize) {
        makeResizable(sobreMimWindow, sobreMimResize);
        console.log('Redimensionamento configurado para Sobre Mim');
    }

    if (trabalhosWindow && trabalhosResize) {
        makeResizable(trabalhosWindow, trabalhosResize);
        console.log('Redimensionamento configurado para Trabalhos');
    }

    if (linksWindow && linksResize) {
        makeResizable(linksWindow, linksResize);
        console.log('Redimensionamento configurado para Links');
    }
    
    // CONFIGURAR ARRASTO PARA TODAS AS JANELAS
    if (sobreMimWindow && sobreMimTitlebar) {
        makeDraggable(sobreMimWindow, sobreMimTitlebar);
        console.log('Arrasto configurado para Sobre Mim');
    }

    if (trabalhosWindow && trabalhosTitlebar) {
        makeDraggable(trabalhosWindow, trabalhosTitlebar);
        console.log('Arrasto configurado para Trabalhos');
    }

    if (linksWindow && linksTitlebar) {
        makeDraggable(linksWindow, linksTitlebar);
        console.log('Arrasto configurado para Links');
    }

    if (musicWindow && musicTitlebar) {
        makeDraggable(musicWindow, musicTitlebar);
        console.log('Arrasto configurado para Music Player');
    }

    // CONFIGURAR BOTÃO "Sobre Mim" NA TASKBAR
    if (sobreMimButton) {
        sobreMimButton.addEventListener('click', function() {
            abrirJanela('sobre-mim-window');
            if (trabalhosButton) trabalhosButton.classList.remove('active');
            if (linksButton) linksButton.classList.remove('active');
        });
    }

    // CONFIGURAR BOTÃO "Trabalhos" NA TASKBAR
    if (trabalhosButton) {
        trabalhosButton.addEventListener('click', function() {
            abrirJanela('trabalhos-window');
            if (sobreMimButton) sobreMimButton.classList.remove('active');
            if (linksButton) linksButton.classList.remove('active');
        });
    }

    // CONFIGURAR BOTÃO "Links" NA TASKBAR
    if (linksButton) {
        linksButton.addEventListener('click', function() {
            abrirJanela('links-window');
            if (sobreMimButton) sobreMimButton.classList.remove('active');
            if (trabalhosButton) trabalhosButton.classList.remove('active');
        });
    }

    // CONFIGURAR BOTÃO "Music Player" NA TASKBAR
    if (musicButton) {
        musicButton.addEventListener('click', function() {
            abrirJanela('music-window');
            // Desativar outros botões
            if (sobreMimButton) sobreMimButton.classList.remove('active');
            if (trabalhosButton) trabalhosButton.classList.remove('active');
            if (linksButton) linksButton.classList.remove('active');
        });
    }

    // CONFIGURAR BOTÃO "Iniciar"
    if (startButton) {
        startButton.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleStartMenu();
            this.classList.toggle('menu-open');
        });
    }

    document.addEventListener('click', function(e) {
    if (startMenuOpen && !e.target.closest('#start-menu') && !e.target.closest('.start-button')) {
        const startButton = document.querySelector('.start-button');
        if (startButton) {
            startButton.classList.remove('menu-open');
        }
    }
});

    // CONFIGURAR ICONE "Sobre Mim" NA ÁREA DE TRABALHO
    if (sobreMimIcon) {
        sobreMimIcon.addEventListener('dblclick', function() {
            abrirJanela('sobre-mim-window');
            if (trabalhosButton) trabalhosButton.classList.remove('active');
            if (linksButton) linksButton.classList.remove('active');
        });
    }

    // CONFIGURAR ICONE "Trabalhos" NA ÁREA DE TRABALHO
    if (trabalhosIcon) {
        trabalhosIcon.addEventListener('dblclick', function() {
            abrirJanela('trabalhos-window');
            if (sobreMimButton) sobreMimButton.classList.remove('active');
            if (linksButton) linksButton.classList.remove('active');
        });
    }

    // CONFIGURAR ICONE "Links" NA ÁREA DE TRABALHO
    if (linksIcon) {
        linksIcon.addEventListener('dblclick', function() {
            abrirJanela('links-window');
            if (sobreMimButton) sobreMimButton.classList.remove('active');
            if (trabalhosButton) trabalhosButton.classList.remove('active');
        });
    }

    // CONFIGURAR ICONE "Music Player" NA ÁREA DE TRABALHO
    if (musicIcon) {
        musicIcon.addEventListener('dblclick', function() {
            abrirJanela('music-window');
            // Desativar outros botões
            if (sobreMimButton) sobreMimButton.classList.remove('active');
            if (trabalhosButton) trabalhosButton.classList.remove('active');
            if (linksButton) linksButton.classList.remove('active');
        });
    }

    // CLIQUE NAS IMAGENS DA GALERIA
    document.querySelectorAll('.gallery-img').forEach(img => {
        img.addEventListener('click', function() {
            abrirModal(this.src);
        });
    });
    
    // FECHAR MODAL
    const closeModal = document.querySelector('.close-modal');
    if (closeModal) {
        closeModal.addEventListener('click', fecharModal);
    }
    
    const imageModal = document.getElementById('image-modal');
    if (imageModal) {
        imageModal.addEventListener('click', function(e) {
            if (e.target === this) fecharModal();
        });
    }
    
    console.log('Script carregado com sucesso!');
});

// MUSIC PLAYER
// VARIÁVEIS GLOBAIS 
let currentSongIndex = 0;
let isPlaying = false;
let audioPlayer = null;

// FUNÇÕES DO MUSIC PLAYER
function initializeMusicPlayer() {
    console.log('Inicializando music player...');
    
    // Criar elemento de áudio
    if (!document.getElementById('audio-player')) {
        const audioElement = document.createElement('audio');
        audioElement.id = 'audio-player';
        document.body.appendChild(audioElement);
    }
    
    audioPlayer = document.getElementById('audio-player');
    if (!audioPlayer) {
        console.error('Elemento de áudio não encontrado');
        return;
    }

    // Configurar eventos do player
    audioPlayer.addEventListener('timeupdate', updateProgress);
    audioPlayer.addEventListener('ended', playNextSong);
    
    // Configurar botões de controle
    document.getElementById('play-btn').addEventListener('click', playSong);
    document.getElementById('pause-btn').addEventListener('click', pauseSong);
    document.getElementById('stop-btn').addEventListener('click', stopSong);
    document.getElementById('prev-btn').addEventListener('click', playPrevSong);
    document.getElementById('next-btn').addEventListener('click', playNextSong);
    
    // Configurar volume
    const volumeSlider = document.getElementById('volume-slider');
    if (volumeSlider) {
        volumeSlider.addEventListener('input', function() {
            audioPlayer.volume = this.value / 100;
        });
        audioPlayer.volume = volumeSlider.value / 100;
    }
    
    // Configurar barra de progresso
    const progressContainer = document.querySelector('.progress-container');
    if (progressContainer) {
        progressContainer.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const clickPosition = (e.clientX - rect.left) / this.offsetWidth;
            audioPlayer.currentTime = clickPosition * audioPlayer.duration;
        });
    }
    
    // Configurar clique nas músicas
    const playlistItems = document.querySelectorAll('#playlist li');
    playlistItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            currentSongIndex = index;
            loadAndPlaySong();
        });
    });
    
    console.log('Music player inicializado com sucesso');
}

function loadAndPlaySong() {
    const playlistItems = document.querySelectorAll('#playlist li');
    if (playlistItems.length === 0) return;
    
    const songSrc = playlistItems[currentSongIndex].getAttribute('data-src');
    
    playlistItems.forEach(item => item.classList.remove('playing'));
    playlistItems[currentSongIndex].classList.add('playing');
    
    document.getElementById('current-song').textContent = playlistItems[currentSongIndex].textContent;
    
    // Carregar e tocar música
    audioPlayer.src = songSrc;
    audioPlayer.load();
    
    playSong().catch(error => {
        console.log('Reprodução automática bloqueada, aguardando interação do usuário');
    });
}

async function playSong() {
    try {
        await audioPlayer.play();
        isPlaying = true;
        const playBtn = document.getElementById('play-btn');
        const pauseBtn = document.getElementById('pause-btn');
        if (playBtn && pauseBtn) {
            playBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-block';
        }
    } catch (error) {
        console.error('Erro ao reproduzir música:', error);
    }
}

function pauseSong() {
    audioPlayer.pause();
    isPlaying = false;
    const playBtn = document.getElementById('play-btn');
    const pauseBtn = document.getElementById('pause-btn');
    if (playBtn && pauseBtn) {
        playBtn.style.display = 'inline-block';
        pauseBtn.style.display = 'none';
    }
}

function stopSong() {
    audioPlayer.pause();
    audioPlayer.currentTime = 0;
    isPlaying = false;
    const playBtn = document.getElementById('play-btn');
    const pauseBtn = document.getElementById('pause-btn');
    if (playBtn && pauseBtn) {
        playBtn.style.display = 'inline-block';
        pauseBtn.style.display = 'none';
    }
}

function playNextSong() {
    const playlistItems = document.querySelectorAll('#playlist li');
    if (playlistItems.length === 0) return;
    
    currentSongIndex = (currentSongIndex + 1) % playlistItems.length;
    loadAndPlaySong();
}

function playPrevSong() {
    const playlistItems = document.querySelectorAll('#playlist li');
    if (playlistItems.length === 0) return;
    
    currentSongIndex = (currentSongIndex - 1 + playlistItems.length) % playlistItems.length;
    loadAndPlaySong();
}

function updateProgress() {
    if (!audioPlayer || isNaN(audioPlayer.duration)) return;
    
    const progressPercent = (audioPlayer.currentTime / audioPlayer.duration) * 100;
    const progressBar = document.getElementById('progress-bar');
    if (progressBar) {
        progressBar.style.width = progressPercent + '%';
    }
    
    // Atualizar tempos
    const currentTimeElement = document.getElementById('current-time');
    const totalTimeElement = document.getElementById('total-time');
    if (currentTimeElement && totalTimeElement) {
        currentTimeElement.textContent = formatTime(audioPlayer.currentTime);
        totalTimeElement.textContent = formatTime(audioPlayer.duration);
    }
}

function formatTime(seconds) {
    if (isNaN(seconds)) return '0:00';
    
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return minutes + ':' + (secs < 10 ? '0' : '') + secs;
}

// FUNÇÃO PARA O ÍCONE MINECRAFT
function setupMinecraftIcon() {
    const minecraftIcon = document.getElementById('minecraft-icon');
    const minecraftButton = document.getElementById('minecraft-button');
    
    const minecraftClick = function(e) {
        e.stopPropagation();
        console.log('💎 Minecraft é realmente o melhor jogo do mundo!');
    };
    
    if (minecraftIcon) minecraftIcon.addEventListener('click', minecraftClick);
    if (minecraftButton) minecraftButton.addEventListener('click', minecraftClick);
}

// FUNÇÃO PARA O ÍCONE DA LIXEIRA
function setupLixeiraIcon() {
    const lixeiraIcon = document.getElementById('lixeira-icon');
    
    if (lixeiraIcon) {
        lixeiraIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            playSound('recycle', 0.6);
            console.log('🗑️ A lixeira está vazia...');
        });
    }
}

// VARIÁVEIS DO MENU INICIAR
let startMenuOpen = false;

// FUNÇÕES DO MENU INICIAR
function toggleStartMenu() {
    const startMenu = document.getElementById('start-menu');
    const overlay = document.getElementById('menu-overlay');
    
    if (!startMenu || !overlay) {
        console.error('Elementos do menu não encontrados!');
        return;
    }
    
    if (!startMenuOpen) {
        // Abrir menu
        startMenu.style.display = 'block';
        overlay.style.display = 'block';
        setTimeout(() => {
            startMenu.classList.add('show');
            overlay.classList.add('show');
        }, 10);
        startMenuOpen = true;
        playSound('windowOpen', 0.5);
    } else {
        // Fechar menu
        fecharStartMenu();
    }
}

function fecharStartMenu() {
    const startMenu = document.getElementById('start-menu');
    const overlay = document.getElementById('menu-overlay');
    
    if (startMenu) {
        startMenu.classList.remove('show');
        overlay.classList.remove('show');
        setTimeout(() => {
            startMenu.style.display = 'none';
            overlay.style.display = 'none';
        }, 300);
    }
    
    startMenuOpen = false;
}

// FECHAR MENU AO CLICAR FORA DELE
function setupMenuClickOutside() {
    document.addEventListener('click', function(e) {
        if (startMenuOpen && 
            !e.target.closest('#start-menu') && 
            !e.target.closest('.start-button')) {
            fecharStartMenu();
        }
    });
}

//RESPONSIVIDADE
function ajustarJanelaParaMobile(janela) {
    if (window.innerWidth <= 768) {
        janela.style.width = '95%';
        janela.style.height = '80vh';
        janela.style.left = '2.5%';
        janela.style.top = '20px';
    }
}

window.addEventListener('resize', function() {
    const janelasAbertas = document.querySelectorAll('.window[style*="display: flex"]');
    janelasAbertas.forEach(janela => {
        if (window.innerWidth <= 768) {
            janela.style.width = '95%';
            janela.style.height = '80vh';
            janela.style.left = '2.5%';
            janela.style.top = '20px';
        } else {
            if (!janela.style.left || janela.style.left === '2.5%') {
                const windowWidth = 700;
                const left = (window.innerWidth - windowWidth) / 2;
                janela.style.left = left + 'px';
                janela.style.top = '50px';
            }
        }
    });
});

function isTouchDevice() {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}

if (isTouchDevice()) {
    document.body.classList.add('touch-device');
    
    const icons = document.querySelectorAll('.icone');
    icons.forEach(icon => {
        icon.style.cursor = 'pointer';
    });
}