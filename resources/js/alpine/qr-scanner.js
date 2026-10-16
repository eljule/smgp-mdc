import { Html5Qrcode, Html5QrcodeSupportedFormats } from 'html5-qrcode';

export default function qrScanner(wire = null) {
    return {
        isOpen: false,
        isStarting: false,
        isScanning: false,
        errorMessage: null,
        hasMultipleCameras: false,
        currentCameraIndex: 0,
        cameras: [],
        html5QrCode: null,
        scannerId: 'qr-reader-container',
        wireInstance: wire,

        async open() {
            this.isOpen = true;
            this.errorMessage = null;
            this.isStarting = true;
            this.isScanning = false;

            if (this.$nextTick) {
                await this.$nextTick();
            } else {
                await new Promise((r) => setTimeout(r, 80));
            }

            setTimeout(() => {
                this.initScanner();
            }, 100);
        },

        async initScanner() {
            try {
                const container = document.getElementById(this.scannerId);
                if (!container) {
                    this.errorMessage = 'No se encontró el visor de la cámara.';
                    this.isStarting = false;
                    return;
                }

                await this.stopScanner();

                this.html5QrCode = new Html5Qrcode(this.scannerId, {
                    formatsToSupport: [
                        Html5QrcodeSupportedFormats.QR_CODE,
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.CODE_39,
                        Html5QrcodeSupportedFormats.EAN_13,
                        Html5QrcodeSupportedFormats.UPC_A
                    ],
                    verbose: false
                });

                try {
                    const devices = await Html5Qrcode.getCameras();
                    if (devices && devices.length > 0) {
                        this.cameras = devices;
                        this.hasMultipleCameras = devices.length > 1;
                    }
                } catch (camErr) {
                    console.warn('No se pudieron listar las cámaras previas:', camErr);
                }

                const config = {
                    fps: 15,
                    qrbox: (viewfinderWidth, viewfinderHeight) => {
                        const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                        const edgeSize = Math.floor(minEdge * 0.75);
                        return { width: edgeSize, height: edgeSize };
                    },
                    aspectRatio: 1.0
                };

                const cameraConfig = (this.cameras.length > 0 && this.cameras[this.currentCameraIndex]?.id)
                    ? this.cameras[this.currentCameraIndex].id
                    : { facingMode: 'environment' };

                await this.html5QrCode.start(
                    cameraConfig,
                    config,
                    (decodedText) => {
                        this.onScanSuccess(decodedText);
                    },
                    () => {}
                );

                this.isStarting = false;
                this.isScanning = true;
            } catch (err) {
                console.error('Error al inicializar cámara:', err);
                this.isStarting = false;
                this.isScanning = false;
                const errStr = (err?.name || '') + ' ' + (err?.message || err?.toString() || '');
                if (errStr.includes('NotAllowedError') || errStr.includes('Permission')) {
                    this.errorMessage = 'Permiso denegado. Habilite el acceso a la cámara en su navegador móvil para continuar.';
                } else if (errStr.includes('NotFoundError') || errStr.includes('DevicesNotFoundError')) {
                    this.errorMessage = 'No se detectó ninguna cámara disponible en este dispositivo.';
                } else if (window.location && window.location.protocol !== 'https:' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                    this.errorMessage = 'El navegador móvil requiere conexión segura (HTTPS) para activar la cámara.';
                } else {
                    this.errorMessage = 'No se pudo iniciar la cámara (' + (err?.message || 'Error desconocido') + ').';
                }
            }
        },

        async switchCamera() {
            if (!this.hasMultipleCameras || this.cameras.length <= 1) return;
            this.currentCameraIndex = (this.currentCameraIndex + 1) % this.cameras.length;
            this.isStarting = true;
            await this.initScanner();
        },

        onScanSuccess(rawText) {
            if (!rawText) return;

            this.playBeep();
            if (navigator && typeof navigator.vibrate === 'function') {
                try { navigator.vibrate([80, 40, 80]); } catch (e) {}
            }

            const cleanCode = this.extractCode(rawText);

            const wire = this.wireInstance || this.$wire || (window.Livewire && this.$el ? window.Livewire.find(this.$el.closest('[wire\\:id]')?.getAttribute('wire:id')) : null);

            if (wire) {
                wire.set('qrCodeInput', cleanCode);
                wire.lookupAsset();
            } else {
                const input = document.querySelector('input[wire\\:model="qrCodeInput"]');
                if (input) {
                    input.value = cleanCode;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }

            this.close();
        },

        extractCode(text) {
            let s = text.trim();

            if (s.includes('/scan/activo/')) {
                const parts = s.split('/scan/activo/');
                if (parts.length > 1) {
                    s = parts[1].split('?')[0].split('#')[0].trim();
                }
            } else if (s.includes('asset=')) {
                try {
                    const url = new URL(s);
                    const asset = url.searchParams.get('asset');
                    if (asset) s = asset;
                } catch (e) {
                    const match = s.match(/[?&]asset=([^&#]+)/);
                    if (match) s = decodeURIComponent(match[1]);
                }
            }

            return s.toUpperCase();
        },

        playBeep() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(1046.5, ctx.currentTime);
                gain.gain.setValueAtTime(0.18, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.15);
                setTimeout(() => ctx.close(), 200);
            } catch (e) {}
        },

        async stopScanner() {
            if (this.html5QrCode) {
                try {
                    if (this.html5QrCode.isScanning) {
                        await this.html5QrCode.stop();
                    }
                    await this.html5QrCode.clear();
                } catch (e) {
                    console.warn('Error deteniendo escáner:', e);
                }
                this.html5QrCode = null;
            }
        },

        async close() {
            await this.stopScanner();
            this.isOpen = false;
            this.isStarting = false;
            this.isScanning = false;
            this.errorMessage = null;
        }
    };
}
