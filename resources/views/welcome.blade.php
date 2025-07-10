@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Добро пожаловать в ToDo Pro</h1>
                <p class="lead mb-4">Управляйте задачами в стильном интерфейсе</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-sign-in-alt me-2"></i> Войти
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">
                        <i class="fas fa-user-plus me-2"></i> Регистрация
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="particles-container" style="height: 400px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Инициализация
            const container = document.getElementById('particles-container');
            const width = container.clientWidth;
            const height = container.clientHeight;

            // Сцена
            const scene = new THREE.Scene();
            scene.background = new THREE.Color(0x0a0a1a);

            // Камера
            const camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
            camera.position.z = 30;

            // Рендерер
            const renderer = new THREE.WebGLRenderer({
                antialias: true,
                alpha: true
            });
            renderer.setSize(width, height);
            container.appendChild(renderer.domElement);

            // Частицы
            const particlesGeometry = new THREE.BufferGeometry();
            const particlesCnt = 2000;

            const posArray = new Float32Array(particlesCnt * 3);
            const colorArray = new Float32Array(particlesCnt * 3);

            for(let i = 0; i < particlesCnt * 3; i++) {
                // Распределение в форме сферы
                const radius = 15;
                posArray[i*3] = (Math.random() - 0.5) * radius * 2;
                posArray[i*3+1] = (Math.random() - 0.5) * radius * 2;
                posArray[i*3+2] = (Math.random() - 0.5) * radius * 2;

                // Цветовая гамма
                colorArray[i*3] = 0.3 + Math.random() * 0.2; // R
                colorArray[i*3+1] = 0.2 + Math.random() * 0.3; // G
                colorArray[i*3+2] = 0.7 + Math.random() * 0.3; // B
            }

            particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
            particlesGeometry.setAttribute('color', new THREE.BufferAttribute(colorArray, 3));

            // Материал частиц
            const particlesMaterial = new THREE.PointsMaterial({
                size: 1.5,
                vertexColors: true,
                transparent: true,
                opacity: 0.9,
                blending: THREE.AdditiveBlending,
                sizeAttenuation: true
            });

            // Система частиц
            const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
            scene.add(particlesMesh);

            // Взаимодействие с мышью
            const mouse = new THREE.Vector2();
            let mouseMoved = false;

            // Правильное определение позиции мыши относительно контейнера
            container.addEventListener('mousemove', (event) => {
                const rect = container.getBoundingClientRect();
                mouse.x = ((event.clientX - rect.left) / container.clientWidth) * 2 - 1;
                mouse.y = -((event.clientY - rect.top) / container.clientHeight) * 2 + 1;
                mouseMoved = true;
            });

            // Анимация
            const clock = new THREE.Clock();
            const originalPositions = posArray.slice();

            function animate() {
                requestAnimationFrame(animate);

                const elapsedTime = clock.getElapsedTime();
                const deltaTime = Math.min(0.1, clock.getDelta());

                // Автоматическое вращение
                particlesMesh.rotation.x = elapsedTime * 0.1;
                particlesMesh.rotation.y = elapsedTime * 0.15;

                // Реакция на мышь
                if(mouseMoved) {
                    const positions = particlesGeometry.attributes.position.array;
                    const mouseStrength = 15;
                    const repulsionRadius = 10;

                    for(let i = 0; i < particlesCnt; i++) {
                        const i3 = i * 3;

                        // Координаты мыши в пространстве сцены
                        const mouseX = mouse.x * mouseStrength;
                        const mouseY = mouse.y * mouseStrength;

                        // Расстояние до мыши в 2D (X,Y)
                        const dx = positions[i3] - mouseX;
                        const dy = positions[i3+1] - mouseY;
                        const distance = Math.sqrt(dx*dx + dy*dy);

                        // Эффект отталкивания
                        if(distance < repulsionRadius) {
                            const force = (repulsionRadius - distance) / repulsionRadius;
                            const pushFactor = force * 8;

                            // Отталкивание по Z-оси
                            positions[i3+2] = originalPositions[i3+2] - pushFactor;

                            // Легкое смещение в X,Y
                            positions[i3] = originalPositions[i3] + (dx/distance) * force * 2;
                            positions[i3+1] = originalPositions[i3+1] + (dy/distance) * force * 2;
                        } else {
                            // Плавное возвращение
                            positions[i3] += (originalPositions[i3] - positions[i3]) * 0.1;
                            positions[i3+1] += (originalPositions[i3+1] - positions[i3+1]) * 0.1;
                            positions[i3+2] += (originalPositions[i3+2] - positions[i3+2]) * 0.1;
                        }
                    }

                    particlesGeometry.attributes.position.needsUpdate = true;
                }

                renderer.render(scene, camera);
            }

            animate();

            // Ресайз
            window.addEventListener('resize', () => {
                camera.aspect = container.clientWidth / container.clientHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(container.clientWidth, container.clientHeight);
            });
        });
    </script>

    <style>
        #particles-container {
            background: #0a0a1a;
            border-radius: 16px;
            overflow: hidden;
            margin: 0 auto;
            max-width: 500px;
            cursor: pointer;
        }

        #particles-container canvas {
            display: block;
            width: 100% !important;
            height: 100% !important;
        }
    </style>
@endsection
